<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\News;
use App\Models\ContactMessage;
use App\Models\Certificate;

class PageController extends Controller
{
    public function home()
    {
        $courses = Course::orderBy('created_at', 'desc')->limit(6)->get();
        $news = News::orderBy('created_at', 'desc')->limit(3)->get();
        return view('pages.home', compact('courses', 'news'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function courses(Request $request)
    {
        $query = Course::query();
        if ($request->category && $request->category !== 'الكل') {
            $query->where('category', $request->category);
        }
        if ($request->level && $request->level !== 'الكل') {
            $query->where('level', $request->level);
        }
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }
        $courses = $query->orderBy('created_at', 'desc')->get();
        return view('pages.courses', compact('courses'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        ContactMessage::create($request->only('name', 'email', 'phone', 'subject', 'message'));

        return back()->with('success', 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.');
    }

    public function verify()
    {
        return view('pages.verify');
    }
}
