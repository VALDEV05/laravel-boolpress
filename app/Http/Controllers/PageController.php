<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Mail\MarkdownContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function contacts()
    {
        return view('guest.contacts.form');
    }
    public function sendContactForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:4|max:50',
            'email' => 'required|email',
            'message' => 'required|min:50|max:500'
        ]);

       //Mail::to('admin@example.com')->send(new ContactFormMail($validated));
        Mail::to('admin@example.com')->send(new MarkdownContactFormMail($validated));
        //ddd($request->all());
        //ddd($validated);
        //return(new ContactFormMail($validated))->render();
        //return(new MarkdownContactFormMail($validated))->render();


        return redirect()->back()->with('message', '🥳  Hai inviato correttamente una mail 🥳 ');
    }
}
 