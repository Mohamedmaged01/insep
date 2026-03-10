<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['student', 'batch']);

        if ($request->batchId) $query->where('batch_id', $request->batchId);
        if ($request->date) $query->where('date', $request->date);
        if ($request->studentId) $query->where('student_id', $request->studentId);

        return response()->json($query->orderBy('date', 'desc')->get());
    }

    public function studentsByBatch($batchId)
    {
        $enrollments = Enrollment::with('student')
            ->where('batch_id', $batchId)
            ->where('status', 'active')
            ->get();

        $students = $enrollments->map(fn($e) => $e->student)->filter();
        return response()->json($students->values());
    }

    public function recordBulk(Request $request)
    {
        $records = $request->input('records', []);
        $results = [];

        foreach ($records as $r) {
            $existing = Attendance::where('batch_id', $r['batchId'] ?? $r['batch_id'] ?? null)
                ->where('student_id', $r['studentId'] ?? $r['student_id'] ?? null)
                ->where('date', $r['date'] ?? null)
                ->first();

            if ($existing) {
                $existing->update([
                    'status' => $r['status'],
                    'notes' => $r['notes'] ?? $existing->notes,
                    'absent_days' => $r['absentDays'] ?? $r['absent_days'] ?? $existing->absent_days,
                ]);
                $results[] = $existing->fresh();
            } else {
                $results[] = Attendance::create([
                    'batch_id' => $r['batchId'] ?? $r['batch_id'],
                    'student_id' => $r['studentId'] ?? $r['student_id'],
                    'date' => $r['date'],
                    'status' => $r['status'],
                    'notes' => $r['notes'] ?? null,
                    'absent_days' => $r['absentDays'] ?? $r['absent_days'] ?? 0,
                ]);
            }
        }

        return response()->json($results);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        Attendance::where('id', $id)->update($data);
        return response()->json(Attendance::with(['student', 'batch'])->find($id));
    }

    public function stats($batchId)
    {
        $all = Attendance::where('batch_id', $batchId)->get();
        $total = $all->count();
        $present = $all->where('status', 'present')->count();
        $absent = $all->where('status', 'absent')->count();
        $excused = $all->where('status', 'excused')->count();

        return response()->json([
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'excused' => $excused,
            'rate' => $total > 0 ? round(($present / $total) * 100) : 0,
        ]);
    }
}
