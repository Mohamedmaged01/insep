<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\News;
use App\Models\ContactMessage;
use App\Models\Certificate;
use App\Models\SiteSetting;

class PageController extends Controller
{
    public function home()
    {
        $courses = Course::orderBy('created_at', 'desc')->limit(6)->get();
        $news = News::orderBy('created_at', 'desc')->limit(3)->get();
        $stats = [
            'students'     => SiteSetting::get('stat_students', '20000'),
            'trainers'     => SiteSetting::get('stat_trainers', '50'),
            'courses'      => SiteSetting::get('stat_courses', '5000'),
            'certificates' => SiteSetting::get('stat_certificates', '2000'),
        ];
        return view('pages.home', compact('courses', 'news', 'stats'));
    }

    public function courseDetail($id)
    {
        $course = Course::findOrFail($id);
        $related = Course::where('category', $course->category)->where('id', '!=', $id)->limit(3)->get();
        return view('pages.course-detail', compact('course', 'related'));
    }

    public function about()
    {
        $settings = SiteSetting::allKeyed();
        return view('pages.about', compact('settings'));
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
        $settings = SiteSetting::allKeyed();
        return view('pages.contact', compact('settings'));
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
