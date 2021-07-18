<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        Mail::to($request->email)->send(new SendEmail());
        echo 'Email sent successfully';
    }
}

/**
 * @OA\Post(
 * path="/send-email",
 * summary="Destination email need to registered first in Authorized Recipients in Mailgun",
 * description="Input destination email",
 * operationId="sendemail",
 * tags={"Send Email with Mailgun (Soal no. 10)"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Email",
 *    @OA\JsonContent(
 *       required={"email","name","password"},
 *       @OA\Property(property="email", type="string", format="email", example="budiharyono4@gmail.com"),
 *    ),
 * ),
 * @OA\Response(
 *   response="200",
 *   description="",
 * ),
 * )
 */
