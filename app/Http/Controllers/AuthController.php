<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
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

        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => 'Email or Password not found'
                ],
                401
            );
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationErrors(new ValidationException($validator));
        }

        User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => app('hash')->make($request->password),
        ]);

        return $this->login($request);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => env('JWT_TTL')
        ]);
    }
}

/**
 * @OA\Post(
 * path="/api/login",
 * summary="Sign in",
 * description="Login by email, password",
 * operationId="authLogin",
 * tags={"Authentication (Soal no. 2)"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Email and Password Credential",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="asdf@zxcv.com"),
 *       @OA\Property(property="password", type="string", format="password", example="123asd"),
 *    ),
 * ),
 * @OA\Response(
 *   response="200",
 *   description="Return JWT Token",
 *   @OA\JsonContent(
 *      @OA\Property(property="auth_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjI2NTk2MjQ5LCJleHAiOjE2MjkyMjQyNDksIm5iZiI6MTYyNjU5NjI0OSwianRpIjoiWGlrczhCQjJrbGdxSkFyTiIsInN1YiI6IjYwZjJhODM3OTZlYTczMWJkZjA4ZmNmOSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.pFNfya7fcSXkyuOZ-obxSyJLSdt8uiYAvKkYiHEEIrg"),
 *       @OA\Property(property="token_type", type="string", example="bearer"),
 *       @OA\Property(property="expires_in", type="string", example="43800"),
 *   )
 * ),
 * @OA\Response(
 *    response=401,
 *    description="Credentials not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=false),
 *       @OA\Property(property="message", type="string", example="Email or Password not found"),
 *       @OA\Property(property="result", type="null", example="null"),
 *       @OA\Property(property="error_code", type="integer", example="1")
 *    )
 *  ),
 * @OA\Response(
 *    response=422,
 *    description="Input validation errors",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=false),
 *       @OA\Property(property="message", type="string", example="The given data was invalid."),
 *       @OA\Property(property="result", type="null", example="null"),
 *       @OA\Property(property="error_code", type="integer", example="1"),
 *       @OA\Property(
 *             property="errors", type="array", example={{
 *                  "field": "email",
 *                  "message": "The email input is required.",
 *                }, {
 *                  "field": "password",
 *                  "message": "The email input is required.",
 *                }},
 *              @OA\Items(
 *                      @OA\Property(
 *                         property="field",
 *                         type="string",
 *                         example="The email field is required."
 *                      ),
 *                      @OA\Property(
 *                         property="message",
 *                         type="string",
 *                         example="The password field is required."
 *                      ),
 *                ),
 *             ),
 *        )
 *     )
 * )
 */

/**
 * @OA\Post(
 * path="/api/register",
 * summary="Register",
 * description="Register using email, name, password",
 * operationId="authRegister",
 * tags={"Authentication (Soal no. 2)"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Email and Password Credential",
 *    @OA\JsonContent(
 *       required={"email","name","password"},
 *       @OA\Property(property="email", type="string", format="email", example="budi@domain.com"),
 *       @OA\Property(property="name", type="string", example="Budi Haryono"),
 *       @OA\Property(property="password", type="string", format="password", example="123asd"),
 *    ),
 * ),
 * @OA\Response(
 *   response="200",
 *   description="Return JWT Token",
 *   @OA\JsonContent(
 *      @OA\Property(property="auth_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjI2NTk2MjQ5LCJleHAiOjE2MjkyMjQyNDksIm5iZiI6MTYyNjU5NjI0OSwianRpIjoiWGlrczhCQjJrbGdxSkFyTiIsInN1YiI6IjYwZjJhODM3OTZlYTczMWJkZjA4ZmNmOSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.pFNfya7fcSXkyuOZ-obxSyJLSdt8uiYAvKkYiHEEIrg"),
 *       @OA\Property(property="token_type", type="string", example="bearer"),
 *       @OA\Property(property="expires_in", type="string", example="43800"),
 *   )
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Input validation errors",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=false),
 *       @OA\Property(property="message", type="string", example="The given data was invalid."),
 *       @OA\Property(property="result", type="null", example="null"),
 *       @OA\Property(property="error_code", type="integer", example="1"),
 *       @OA\Property(
 *             property="errors", type="array", example={{
 *                  "field": "email",
 *                  "message": "The email input is required.",
 *                }, {
 *                  "field": "password",
 *                  "message": "The email input is required.",
 *                }},
 *              @OA\Items(
 *                      @OA\Property(
 *                         property="field",
 *                         type="string",
 *                         example="The email field is required."
 *                      ),
 *                      @OA\Property(
 *                         property="message",
 *                         type="string",
 *                         example="The password field is required."
 *                      ),
 *                ),
 *             ),
 *        )
 *     )
 * )
 */

/**
 * @OA\Post(
 * path="/api/logout",
 * summary="Logout",
 * description="Logout user and invalidate token",
 * operationId="authLogout",
 * tags={"Authentication (Soal no. 2)"},
 * security={{"bearerAuth":{}}},
 * @OA\Response(
 *    response=200,
 *    description="Success",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Successfully logged out")
 *    )
 * ),
 * @OA\Response(
 *    response=401,
 *    description="Unauthorized.",
 * )
 * )
 */
