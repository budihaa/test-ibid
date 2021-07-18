<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index()
    {
        $email = Mail::to('budiharyono4@gmail.com')->send(new SendEmail());
        echo '<pre>';
        print_r($email);
        echo '<pre>';
    }
}
