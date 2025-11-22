<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'message' => 'required|string|min:5',
        ]);

        // Process the contact form
        $name = $request->input('name', 'Guest');
        $email = $request->input('email');
        $message = $request->input('message');

        // Here you would typically save to database or send email
        // For now, we'll just redirect back with success message

        return back()->with('flash', "Thanks, {$name}. Your message was received.");
    }
}
