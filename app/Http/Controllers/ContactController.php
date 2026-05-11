<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
class ContactController extends Controller
{
    // عرض الصفحة
    public function index()
    {
        return view('contact');
    }

    // إرسال الفورم
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);
    
        ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);
    
        return back()->with('success', 'Message sent successfully 🎉');
    }
}