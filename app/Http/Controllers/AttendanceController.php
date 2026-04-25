<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = Attendance::with(['student', 'batch']);

        if ($user->role === 'instructor') {
            $ownBatchIds = Batch::where('instructor_id', $user->id)->pluck('id');
            $query->whereIn('batch_id', $ownBatchIds);
        } elseif ($user->role === 'student') {
            // Students see only their own attendance records
            $query->where('student_id', $user->id);
        }

        if ($request->batchId)   $query->where('batch_id', $request->batchId);
        if ($request->date)      $query->where('date', $request->date);
        if ($request->studentId) $query->where('student_id', $request->studentId);

        return response()->json($query->orderBy('date', 'desc')->get());
    }

    public function studentsByBatch(Request $request, $batchId)
    {
        $user  = $request->user();
        $batch = Batch::findOrFail($batchId);

        if ($user->role === 'instructor') {
            abort_if($batch->instructor_id !== $user->id, 403);
        } elseif ($user->role === 'student') {
            abort_if(
                !Enrollment::where('student_id', $user->id)->where('batch_id', $batchId)->exists(),
                403
            );
        }

        $enrollments = Enrollment::with('student')
            ->where('batch_id', $batchId)
            ->where('status', 'active')
            ->get();

        $students = $enrollments->map(fn($e) => $e->student)->filter();
        return response()->json($students->values());
    }

    public function recordBulk(Request $request)
    {
        $user    = $request->user();
        $records = $request->input('records', []);

        // Collect the batch IDs being written and verify ownership for instructors
        if ($user->role === 'instructor') {
            $ownBatchIds = Batch::where('instructor_id', $user->id)->pluck('id');
            foreach ($records as $r) {
                $batchId = $r['batchId'] ?? $r['batch_id'] ?? null;
                abort_if(!$ownBatchIds->contains($batchId), 403);
            }
        }

        $results = [];
        foreach ($records as $r) {
            $existing = Attendance::where('batch_id', $r['batchId'] ?? $r['batch_id'] ?? null)
                ->where('student_id', $r['studentId'] ?? $r['student_id'] ?? null)
                ->where('date', $r['date'] ?? null)
                ->first();

            if ($existing) {
                $existing->update([
                    'status'      => $r['status'],
                    'notes'       => $r['notes'] ?? $existing->notes,
                    'absent_days' => $r['absentDays'] ?? $r['absent_days'] ?? $existing->absent_days,
                ]);
                $results[] = $existing->fresh();
            } else {
                $results[] = Attendance::create([
                    'batch_id'    => $r['batchId'] ?? $r['batch_id'],
                    'student_id'  => $r['studentId'] ?? $r['student_id'],
                    'date'        => $r['date'],
                    'status'      => $r['status'],
                    'notes'       => $r['notes'] ?? null,
                    'absent_days' => $r['absentDays'] ?? $r['absent_days'] ?? 0,
                ]);
            }
        }

        return response()->json($results);
    }

    public function update(Request $request, $id)
    {
        $user       = $request->user();
        $attendance = Attendance::findOrFail($id);

        if ($user->role === 'instructor') {
            $ownBatchIds = Batch::where('instructor_id', $user->id)->pluck('id');
            abort_if(!$ownBatchIds->contains($attendance->batch_id), 403);
        }

        $attendance->update($request->all());
        return response()->json($attendance->fresh()->load(['student', 'batch']));
    }

    public function stats(Request $request, $batchId)
    {
        $user  = $request->user();
        $batch = Batch::findOrFail($batchId);

        if ($user->role === 'instructor') {
            abort_if($batch->instructor_id !== $user->id, 403);
        } elseif ($user->role === 'student') {
            abort_if(
                !Enrollment::where('student_id', $user->id)->where('batch_id', $batchId)->exists(),
                403
            );
        }

        $all     = Attendance::where('batch_id', $batchId)->get();
        $total   = $all->count();
        $present = $all->where('status', 'present')->count();
        $absent  = $all->where('status', 'absent')->count();
        $excused = $all->where('status', 'excused')->count();

        return response()->json([
            'total'   => $total,
            'present' => $present,
            'absent'  => $absent,
            'excused' => $excused,
            'rate'    => $total > 0 ? round(($present / $total) * 100) : 0,
        ]);
    }
}
