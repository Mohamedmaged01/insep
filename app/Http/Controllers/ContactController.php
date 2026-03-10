<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $msg = ContactMessage::create($request->all());
        return response()->json($msg);
    }

    public function index()
    {
        return response()->json(ContactMessage::orderBy('created_at', 'desc')->get());
    }

    public function markRead($id)
    {
        ContactMessage::where('id', $id)->update(['is_read' => true]);
        return response()->json(ContactMessage::find($id));
    }

    public function destroy($id)
    {
        ContactMessage::destroy($id);
        return response()->json(['deleted' => true]);
    }
}
