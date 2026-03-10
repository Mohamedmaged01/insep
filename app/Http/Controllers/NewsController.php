<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
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
        return response()->json(News::create($request->all()));
    }

    public function update(Request $request, $id)
    {
        News::where('id', $id)->update($request->all());
        return response()->json(News::find($id));
    }

    public function destroy($id)
    {
        News::destroy($id);
        return response()->json(['deleted' => true]);
    }
}
