<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        $email = Mail::to($request->email)->send(new SendEmail());
        echo 'Email sent successfully';
    }
}
