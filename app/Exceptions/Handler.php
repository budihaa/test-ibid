<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        // AuthorizationException::class,
        // HttpException::class,
        // ModelNotFoundException::class,
        // ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        $ignoreable_exception_messages = ['Unauthenticated or Token Expired, Please Login'];
        $ignoreable_exception_messages[] = 'The resource owner or authorization server denied the request.';
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            if (!in_array($exception->getMessage(), $ignoreable_exception_messages)) {
                app('sentry')->captureException($exception);
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof PostTooLargeException) {
                return $this->apiResponse(
                    [
                        'success' => false,
                        'message' => "Size of attached file should be less " . ini_get("upload_max_filesize") . "B"
                    ],
                    400
                );
            }

            if ($exception instanceof AuthenticationException) {
                return $this->apiResponse(
                    [
                        'success' => false,
                        'message' => 'Unauthenticated or Token Expired, Please Login'
                    ],
                    401
                );
            }

            if ($exception instanceof ThrottleRequestsException) {
                return $this->apiResponse(
                    [
                        'success' => false,
                        'message' => 'Too Many Requests,Please Slow Down'
                    ],
                    429
                );
            }

            if ($exception instanceof ModelNotFoundException) {
                return $this->apiResponse(
                    [
                        'success' => false,
                        'message' => 'Entry for ' . str_replace('App\\', '', $exception->getModel()) . ' not found'
                    ],
                    404
                );
            }

            if ($exception instanceof ValidationException) {
                return $this->apiResponse(
                    [
                        'success' => false,
                        'message' => $exception->getMessage(),
                        'errors' => $exception->errors()
                    ],
                    422
                );
            }

            if ($exception instanceof QueryException) {
                return $this->apiResponse(
                    [
                        'success' => false,
                        'message' => 'There was Issue with the Query',
                        'exception' => $exception

                    ],
                    500
                );
            }

            if ($exception instanceof \Error) {
                // $exception = $exception->getResponse();
                return $this->apiResponse(
                    [
                        'success' => false,
                        'message' => "There was some internal error",
                        'exception' => $exception
                    ],
                    500
                );
            }
        }

        return parent::render($request, $exception);
    }
}
