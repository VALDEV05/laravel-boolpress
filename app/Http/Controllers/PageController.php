<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Mail\MarkdownContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
   public function index()
   {
      return view('guest.welcome');
   }
   public function about()
   {
      return view('guest.about.index');
   }
}
 