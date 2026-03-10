<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json(
            Notification::with(['user', 'batch'])
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function my(Request $request)
    {
        return response()->json(
            Notification::with('batch')
                ->where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function unreadCount(Request $request)
    {
        $count = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();
        return response()->json($count);
    }

    public function sendToBatch(Request $request, $batchId)
    {
        $enrollments = Enrollment::where('batch_id', $batchId)
            ->where('status', 'active')
            ->get();

        $notifications = [];
        foreach ($enrollments as $enrollment) {
            $notifications[] = Notification::create([
                'user_id' => $enrollment->student_id,
                'text' => $request->text,
                'type' => $request->type ?? 'general',
                'batch_id' => $batchId,
            ]);
        }

        return response()->json([
            'sent' => count($notifications),
            'notifications' => $notifications,
        ]);
    }

    public function markRead($id)
    {
        Notification::where('id', $id)->update(['is_read' => true]);
        return response()->json(Notification::find($id));
    }
}
