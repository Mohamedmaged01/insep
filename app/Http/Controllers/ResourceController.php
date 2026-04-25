<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Enrollment;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Resource::with(['instructor', 'course', 'batch']);

        if ($user->role === 'admin') {
            // admin sees everything
        } elseif ($user->role === 'instructor') {
            $batchIds  = Batch::where('instructor_id', $user->id)->pluck('id');
            $courseIds = Batch::where('instructor_id', $user->id)->pluck('course_id')->filter()->unique();
            $query->where(function ($q) use ($batchIds, $courseIds) {
                $q->whereIn('batch_id', $batchIds)
                  ->orWhereIn('course_id', $courseIds);
            });
        } elseif ($user->role === 'student') {
            $enrollments = Enrollment::where('student_id', $user->id)->get();
            $batchIds    = $enrollments->pluck('batch_id')->filter()->unique();
            $courseIds   = $enrollments->pluck('course_id')->filter()->unique();
            $query->where(function ($q) use ($batchIds, $courseIds) {
                $q->whereIn('batch_id', $batchIds)
                  ->orWhereIn('course_id', $courseIds);
            });
        } else {
            return response()->json([]);
        }

        if ($request->courseId) $query->where('course_id', $request->courseId);
        if ($request->type)     $query->where('type', $request->type);
        if ($request->batchId)  $query->where('batch_id', $request->batchId);

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['instructor_id'] = $data['instructor_id'] ?? $data['instructorId'] ?? $request->user()->id;
        if (isset($data['courseId'])) $data['course_id'] = $data['courseId'];
        if (isset($data['batchId'])) $data['batch_id'] = $data['batchId'];

        $resource = Resource::create($data);
        return response()->json($resource);
    }

    public function upload(Request $request)
    {
        $request->validate(['file' => 'required|file|max:512000']); // 500MB

        $file = $request->file('file');
        $filename = time() . '-' . rand(100000000, 999999999) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);

        $resource = Resource::create([
            'title'         => $request->input('title', $file->getClientOriginalName()),
            'type'          => $request->input('type', 'Document'),
            'file_url'      => '/uploads/' . $filename,
            'size'          => round($file->getSize() / 1024 / 1024, 1) . ' MB',
            'instructor_id' => $request->input('instructorId', $request->input('instructor_id', $request->user()->id)),
            'course_id'     => $request->input('courseId', $request->input('course_id')),
            'batch_id'      => $request->input('batchId', $request->input('batch_id')),
        ]);

        return response()->json($resource);
    }

    public function destroy($id)
    {
        $user     = request()->user();
        $resource = Resource::findOrFail($id);

        if ($user->role !== 'admin') {
            $ownBatchIds  = Batch::where('instructor_id', $user->id)->pluck('id');
            $ownCourseIds = Batch::where('instructor_id', $user->id)->pluck('course_id')->filter()->unique();
            abort_if(
                !$ownBatchIds->contains($resource->batch_id) && !$ownCourseIds->contains($resource->course_id),
                403
            );
        }

        $resource->delete();
        return response()->json(['deleted' => true]);
    }

    public function download($id)
    {
        $user     = request()->user();
        $resource = Resource::findOrFail($id);

        if ($user->role === 'student') {
            $enrollments = Enrollment::where('student_id', $user->id)->get();
            $batchIds    = $enrollments->pluck('batch_id')->filter()->unique();
            $courseIds   = $enrollments->pluck('course_id')->filter()->unique();
            abort_if(
                !$batchIds->contains($resource->batch_id) && !$courseIds->contains($resource->course_id),
                403
            );
        } elseif ($user->role === 'instructor') {
            $ownBatchIds  = Batch::where('instructor_id', $user->id)->pluck('id');
            $ownCourseIds = Batch::where('instructor_id', $user->id)->pluck('course_id')->filter()->unique();
            abort_if(
                !$ownBatchIds->contains($resource->batch_id) && !$ownCourseIds->contains($resource->course_id),
                403
            );
        }

        Resource::where('id', $id)->increment('downloads');
        return response()->json($resource);
    }
}
