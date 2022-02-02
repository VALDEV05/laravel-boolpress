<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\MarkdownContactFormMail;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function show_contact_page()
    {
        return view('guest.contacts.form');
    }
    public function store(Request $request)
    {   
        //Validazione
        $validated = $request->validate([
            'name' => 'required|min:4|max:50',
            'email' => 'required|email',
            'message' => 'required|min:50|max:500'
        ]);
        //Creazione risorsa
        $contact = Contact::create($validated);

        Mail::to('admin@example.com')->send(new MarkdownContactFormMail($contact));
        


        return redirect()->back()->with('message', '🥳  Hai inviato correttamente una mail 🥳 ');
    }
}
