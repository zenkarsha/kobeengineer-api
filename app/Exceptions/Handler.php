<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            return response()->json(['error' => [
                'message' => 'Validation Token was expired. Please try again.',
            ]]);
        }

        if($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
        {
            return response()->json(['error' => [
                'message' => 'Unsupported request.',
            ]]);
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException)
        {
            return response()->json(['error' => [
                'message' => 'Method not allowed.',
            ]]);
        }

        if (env('APP_DEBUG') === true) {
            if ($e instanceof ModelNotFoundException) {
                $e = new NotFoundHttpException($e->getMessage(), $e);
            }
            return parent::render($request, $e);
        } else {
            if (\Input::get('debug_key') && \Input::get('debug_key') == env('APP_KEY')) {
                return response()->json(['error' => [
                    'message' => $e->getMessage(),
                ]]);
            }
            else {
                return response()->json(['error' => [
                    'message' => 'An unknown error has occurred.',
                ]]);
            }
        }
    }
}
