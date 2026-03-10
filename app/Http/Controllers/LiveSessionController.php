<?php

namespace App\Http\Controllers;

use App\Models\LiveSession;
use App\Models\Notification;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class LiveSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = LiveSession::with(['batch', 'instructor']);

        if ($request->user()->role === 'instructor') {
            $query->where('instructor_id', $request->user()->id);
        }
        if ($request->batchId) {
            $query->where('batch_id', $request->batchId);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['instructor_id'] = $data['instructor_id'] ?? $data['instructorId'] ?? $request->user()->id;
        if (isset($data['batchId'])) $data['batch_id'] = $data['batchId'];
        if (isset($data['liveUrl'])) $data['live_url'] = $data['liveUrl'];
        if (isset($data['scheduledAt'])) $data['scheduled_at'] = $data['scheduledAt'];

        $session = LiveSession::create($data);

        // Auto-notify enrolled students
        if ($session->batch_id) {
            $enrollments = Enrollment::where('batch_id', $session->batch_id)
                ->where('status', 'active')
                ->get();

            foreach ($enrollments as $enrollment) {
                Notification::create([
                    'user_id' => $enrollment->student_id,
                    'text' => "📺 بث مباشر جديد: {$session->title} — الرابط: {$session->live_url}",
                    'type' => 'live',
                    'batch_id' => $session->batch_id,
                ]);
            }
        }

        return response()->json($session);
    }

    public function update(Request $request, $id)
    {
        $session = LiveSession::find($id);
        if (!$session) return response()->json(['message' => 'الجلسة غير موجودة'], 404);

        $data = $request->all();
        if (isset($data['liveUrl'])) $data['live_url'] = $data['liveUrl'];
        if (isset($data['batchId'])) $data['batch_id'] = $data['batchId'];
        if (isset($data['scheduledAt'])) $data['scheduled_at'] = $data['scheduledAt'];

        $session->update($data);
        return response()->json(LiveSession::with(['batch', 'instructor'])->find($id));
    }

    public function destroy($id)
    {
        $session = LiveSession::find($id);
        if (!$session) return response()->json(['message' => 'الجلسة غير موجودة'], 404);
        $session->delete();
        return response()->json(['deleted' => true]);
    }
}
