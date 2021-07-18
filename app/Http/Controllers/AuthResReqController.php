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
