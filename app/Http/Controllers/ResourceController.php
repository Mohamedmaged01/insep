<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::with(['instructor', 'course', 'batch']);

        if ($request->user()->role === 'instructor') {
            $query->where('instructor_id', $request->user()->id);
        }
        if ($request->courseId) $query->where('course_id', $request->courseId);
        if ($request->type) $query->where('type', $request->type);
        if ($request->batchId) $query->where('batch_id', $request->batchId);

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
            'title' => $request->input('title', $file->getClientOriginalName()),
            'type' => $request->input('type', 'Document'),
            'file_url' => '/uploads/' . $filename,
            'size' => round($file->getSize() / 1024 / 1024, 1) . ' MB',
            'instructor_id' => $request->input('instructorId', $request->input('instructor_id', $request->user()->id)),
            'course_id' => $request->input('courseId', $request->input('course_id')),
            'batch_id' => $request->input('batchId', $request->input('batch_id')),
        ]);

        return response()->json($resource);
    }

    public function destroy($id)
    {
        Resource::destroy($id);
        return response()->json(['deleted' => true]);
    }

    public function download($id)
    {
        Resource::where('id', $id)->increment('downloads');
        return response()->json(Resource::find($id));
    }
}
