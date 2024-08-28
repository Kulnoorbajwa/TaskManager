<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        if (env('APP_ENV') == 'production') {


            // Handle unauthorized exceptions
            $this->renderable(function (UnauthorizedException $e, Request $request) {
                return response()->view('errors.403', [], 403);
            });

            // Handle HTTP exceptions
            $this->renderable(function (HttpExceptionInterface $e, Request $request) {
                $statusCode = $e->getStatusCode();
                if (view()->exists("errors.{$statusCode}")) {
                    return response()->view("errors.{$statusCode}", [], $statusCode);
                }
                return response()->view('errors.500', [], 500);
            });

            // Handle all other exceptions
            $this->renderable(function (Throwable $e, Request $request) {
                return response()->view('errors.500', [], 500);
            });
        }
    }
}
