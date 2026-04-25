<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Enrollment;
use App\Models\LiveSession;
use App\Models\Notification;
use Illuminate\Http\Request;

class LiveSessionController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = LiveSession::with(['batch', 'instructor']);

        if ($user->role === 'instructor') {
            $query->where('instructor_id', $user->id);
        } elseif ($user->role === 'student') {
            $batchIds = Enrollment::where('student_id', $user->id)->pluck('batch_id');
            $query->whereIn('batch_id', $batchIds);
        }

        if ($request->batchId) {
            $query->where('batch_id', $request->batchId);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->all();
        $data['instructor_id'] = $data['instructor_id'] ?? $data['instructorId'] ?? $user->id;
        if (isset($data['batchId']))     $data['batch_id']     = $data['batchId'];
        if (isset($data['liveUrl']))     $data['live_url']     = $data['liveUrl'];
        if (isset($data['scheduledAt'])) $data['scheduled_at'] = $data['scheduledAt'];

        // Instructors may only create sessions for their own batches
        if ($user->role === 'instructor' && !empty($data['batch_id'])) {
            abort_if(
                !Batch::where('id', $data['batch_id'])->where('instructor_id', $user->id)->exists(),
                403
            );
        }

        $session = LiveSession::create($data);

        if ($session->batch_id) {
            $enrollments = Enrollment::where('batch_id', $session->batch_id)
                ->where('status', 'active')
                ->get();

            foreach ($enrollments as $enrollment) {
                Notification::create([
                    'user_id'  => $enrollment->student_id,
                    'text'     => "📺 بث مباشر جديد: {$session->title} — الرابط: {$session->live_url}",
                    'type'     => 'live',
                    'batch_id' => $session->batch_id,
                ]);
            }
        }

        return response()->json($session);
    }

    public function update(Request $request, $id)
    {
        $user    = $request->user();
        $session = LiveSession::find($id);

        if (!$session) return response()->json(['message' => 'الجلسة غير موجودة'], 404);

        if ($user->role === 'instructor') {
            abort_if($session->instructor_id !== $user->id, 403);
        }

        $data = $request->all();
        if (isset($data['liveUrl']))     $data['live_url']     = $data['liveUrl'];
        if (isset($data['batchId']))     $data['batch_id']     = $data['batchId'];
        if (isset($data['scheduledAt'])) $data['scheduled_at'] = $data['scheduledAt'];

        $session->update($data);
        return response()->json(LiveSession::with(['batch', 'instructor'])->find($id));
    }

    public function destroy(Request $request, $id)
    {
        $user    = $request->user();
        $session = LiveSession::find($id);

        if (!$session) return response()->json(['message' => 'الجلسة غير موجودة'], 404);

        if ($user->role === 'instructor') {
            abort_if($session->instructor_id !== $user->id, 403);
        }

        $session->delete();
        return response()->json(['deleted' => true]);
    }
}
