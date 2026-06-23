<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Map incoming request fields onto the news columns, supporting both the
     * canonical names (description/image/video_url) and the older mobile aliases
     * (body/content/image_url). Handles uploaded image files when present.
     */
    private function payload(Request $request): array
    {
        $data = array_filter([
            'title'       => $request->input('title'),
            'description' => $request->input('description', $request->input('body', $request->input('content'))),
            'tag'         => $request->input('tag'),
            'date'        => $request->input('date'),
            'video_url'   => $request->input('video_url'),
        ], fn($v) => $v !== null);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        } elseif ($request->filled('image')) {
            $data['image'] = $request->input('image');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->input('image_url');
        }

        return $data;
    }

    public function index()
    {
        return response()->json(News::orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $news = News::find($id);
        if (!$news) return response()->json(['message' => 'الخبر غير موجود'], 404);
        return response()->json($news);
    }

    public function store(Request $request)
    {
        $data = $this->payload($request);
        if (empty($data['date'])) {
            $data['date'] = now()->toDateString();
        }
        return response()->json(News::create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        $data = $this->payload($request);
        // If a new image file replaced an old uploaded one, remove the old file
        if (isset($data['image']) && $news->image && !str_starts_with($news->image, 'http') && $request->hasFile('image')) {
            Storage::disk('public')->delete($news->image);
        }
        $news->update($data);
        return response()->json($news->fresh());
    }

    public function destroy($id)
    {
        $news = News::find($id);
        if ($news) {
            if ($news->image && !str_starts_with($news->image, 'http')) {
                Storage::disk('public')->delete($news->image);
            }
            $news->delete();
        }
        return response()->json(['deleted' => true]);
    }
}
