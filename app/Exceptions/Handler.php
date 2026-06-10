<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    /**
     * Render an exception into an HTTP response.
     *
     * @param  mixed  $request
     */
    public function render($request, Throwable $e)
    {
        /** @var Request $request */
        if ($e instanceof TokenMismatchException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Page Expired',
                ], 419);
            }

            $redirectResponse = redirect()->back()
                ->with('session_expired', true)
                ->with('error', 'Sesi sudah expired. Silakan login ulang atau muat ulang halaman.');

            return $redirectResponse;
        }

        return parent::render($request, $e);
    }

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
