<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function verify($serial)
    {
        $cert = Certificate::with(['student', 'course'])->where('serial_number', $serial)->first();
        if (!$cert) return response()->json(['found' => false]);

        return response()->json([
            'found' => true,
            'certificate' => [
                'serialNumber' => $cert->serial_number,
                'title' => $cert->title,
                'studentName' => $cert->student?->name,
                'courseName' => $cert->course?->title,
                'issueDate' => $cert->issue_date,
                'grade' => $cert->grade,
                'status' => $cert->status,
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

    public function update(Request $request, $id)
    {
        Certificate::where('id', $id)->update($request->all());
        return response()->json(Certificate::with(['student', 'course'])->find($id));
    }

    public function destroy($id)
    {
        Certificate::destroy($id);
        return response()->json(['deleted' => true]);
    }
}
