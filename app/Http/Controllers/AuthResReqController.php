<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use GuzzleHttp\Exception\RequestException;

class AuthResReqController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationErrors(new ValidationException($validator));
        }

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://reqres.in/api/login', [
                'form_params' => [
                    'email' => $request->email,
                    'password' => $request->password,
                ]
            ]);
            $body = json_decode($response->getBody());
            return response([
                'token' => $body->token
            ]);
        } catch (RequestException $e) {
            $errorBody = json_decode($e->getResponse()->getBody(true));
            return response([
                'error' => $errorBody->error
            ], $e->getResponse()->getStatusCode());
        } catch (\Exception $e) {
            return response([
                'error' => 'Internal Server Error'
            ], 500);
        }
    }

    public function register(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationErrors(new ValidationException($validator));
        }

                try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://reqres.in/api/register', [
                'form_params' => [
                    'email' => $request->email,
                    'password' => $request->password,
                ]
            ]);
            $body = json_decode($response->getBody());
            return response([
                'id' => $body->id,
                'token' => $body->token
            ]);
        } catch (RequestException $e) {
            $errorBody = json_decode($e->getResponse()->getBody(true));
            return response([
                'error' => $errorBody->error
            ], $e->getResponse()->getStatusCode());
        } catch (\Exception $e) {
            return response([
                'error' => 'Internal Server Error'
            ], 500);
        }
    }
}

/**
 * @OA\Post(
 * path="/api/resreq-login",
 * summary="Sign in",
 * description="Login by email, password",
 * operationId="authLoginRes",
 * tags={"Authentication resreq.in (Soal no. 6)"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Email and Password Credential",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="eve.holt@reqres.in"),
 *       @OA\Property(property="password", type="string", format="password", example="cityslicka"),
 *    ),
 * ),
 * @OA\Response(
 *   response="200",
 *   description="Return Token",
 *   @OA\JsonContent(
 *      @OA\Property(property="token", type="string", example="QpwL5tke4Pnpja7X4"),
 *   )
 * ),
 * @OA\Response(
 *    response=400,
 *    description="Credentials not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="error", type="string", example="user not found"),
 *    )
 *  ),
 * )
 */

 /**
 * @OA\Post(
 * path="/api/resreq-register",
 * summary="Register",
 * description="Register using email, and password",
 * operationId="authRegisterRes",
 * tags={"Authentication resreq.in (Soal no. 6)"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Email and Password",
 *    @OA\JsonContent(
 *       required={"email","name","password"},
 *       @OA\Property(property="email", type="string", format="email", example="eve.holt@reqres.in"),
 *       @OA\Property(property="password", type="string", format="password", example="pistol"),
 *    ),
 * ),
 * @OA\Response(
 *   response="200",
 *   description="Return Token",
 *   @OA\JsonContent(
 *      @OA\Property(property="id", type="integer", example="1"),
 *      @OA\Property(property="token", type="string", example="QpwL5tke4Pnpja7X4"),
 *   )
 * ),
 * @OA\Response(
 *    response=400,
 *    description="Only defined users succeed registration",
 *      @OA\Property(property="error", type="string", example="Note: Only defined users succeed registration"),
 *     )
 * )
 */
