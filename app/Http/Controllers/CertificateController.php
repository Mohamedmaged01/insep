<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function verify($serial)
    {
        $q = trim($serial);

        // Exact match first, then LIKE fallback (supports partial codes and both formats)
        $cert = Certificate::with(['student', 'course'])
            ->where(fn($query) => $query
                ->where('serial_number', $q)
                ->orWhere('serial_number', 'like', '%' . $q . '%')
            )
            ->orderByRaw("CASE WHEN serial_number = ? THEN 0 ELSE 1 END", [$q])
            ->first();

        if (!$cert) return response()->json(['found' => false]);

        $student = $cert->student;
        $studentName = $student
            ? ($student->name_ar ?? $student->name_en ?? $student->name ?? '')
            : ($cert->student_name ?? '');

        return response()->json([
            'found' => true,
            'certificate' => [
                'serialNumber' => $cert->serial_number,
                'title'        => $cert->title,
                'studentName'  => $studentName,
                'courseName'   => $cert->course?->title,
                'issueDate'    => $cert->issue_date,
                'grade'        => $cert->grade,
                'status'       => $cert->status,
                'fileUrl'      => $cert->file_url,
            ],
        ]);
    }

    public function index(Request $request)
    {
        if ($request->user()->role === 'student') {
            return response()->json(
                Certificate::with('course')
                    ->where('student_id', $request->user()->id)
                    ->orderBy('created_at', 'desc')
                    ->get()
            );
        }

        return response()->json(
            Certificate::with(['student', 'course'])
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (empty($data['serial_number'])) {
            $data['serial_number'] = 'INSEP-' . time() . '-' . strtoupper(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 4));
        }
        $cert = Certificate::create($data);
        return response()->json($cert);
    }

    /**
     * Upload a certificate file for a specific student (individual upload).
     * Accepts multipart form-data with a real file. No PDF is generated.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
            'student_id'       => 'required|exists:users,id',
        ]);

        $path    = $request->file('certificate_file')->store('certificates', 'public');
        $fileUrl = Storage::url($path);

        $cert = Certificate::create([
            'serial_number' => $request->serial_number
                ?: 'INSEP-' . time() . '-' . strtoupper(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 4)),
            'student_id'    => $request->student_id,
            'course_id'     => $request->course_id ?: null,
            'batch_id'      => $request->batch_id ?: null,
            'title'         => $request->title ?: 'شهادة إتمام الدورة',
            'issue_date'    => $request->issue_date ?: now()->toDateString(),
            'grade'         => $request->grade ?: null,
            'status'        => 'active',
            'file_url'      => $fileUrl,
            'type'          => 'manual',
            'created_by'    => $request->user()->id,
        ]);

        return response()->json($cert->load(['student', 'course']), 201);
    }

    public function update(Request $request, $id)
    {
        Certificate::where('id', $id)->update($request->all());
        return response()->json(Certificate::with(['student', 'course'])->find($id));
    }

    public function destroy($id)
    {
        $cert = Certificate::find($id);
        if ($cert) {
            if ($cert->file_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', parse_url($cert->file_url, PHP_URL_PATH)));
            }
            $cert->delete();
        }
        return response()->json(['deleted' => true]);
    }

    /**
     * Attach / replace the file on an existing certificate (individual upload to a row).
     */
    public function uploadFile($id, Request $request)
    {
        $request->validate([
            'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ]);

        $cert = Certificate::findOrFail($id);

        if ($cert->file_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', parse_url($cert->file_url, PHP_URL_PATH)));
        }

        $path = $request->file('certificate_file')->store('certificates', 'public');
        $cert->update(['file_url' => Storage::url($path), 'type' => 'manual']);

        return response()->json($cert->load(['student', 'course']));
    }
}
