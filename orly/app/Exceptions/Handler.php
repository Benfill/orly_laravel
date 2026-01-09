<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Check if request expects JSON (API request)
        if ($request->is('api/*') || $request->expectsJson()) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle API exceptions and return JSON responses
     */
    private function handleApiException($request, Throwable $exception)
    {
        // Validation Exception
        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $exception->errors()
            ], 422);
        }

        // Authentication Exception
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'error' => 'You must be logged in to access this resource.'
            ], 401);
        }

        // Authorization Exception (Forbidden)
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
                'error' => 'You do not have permission to access this resource.'
            ], 403);
        }

        // Model Not Found Exception
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
                'error' => 'The requested resource does not exist.'
            ], 404);
        }

        // Route Not Found Exception
        if ($exception instanceof RouteNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'error' => 'Authentication required to access this resource.'
            ], 401);
        }

        // Not Found Exception
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint not found.',
                'error' => 'The requested endpoint does not exist.'
            ], 404);
        }

        // Method Not Allowed Exception
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed.',
                'error' => 'The ' . $request->method() . ' method is not supported for this endpoint.',
                'allowed_methods' => $exception->getHeaders()['Allow'] ?? []
            ], 405);
        }

        // HTTP Exception (covers 400, 500, etc.)
        if ($exception instanceof HttpException) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage() ?: 'HTTP error occurred.',
                'error' => $exception->getMessage()
            ], $exception->getStatusCode());
        }

        // Generic Exception - catch all
        $statusCode = method_exists($exception, 'getStatusCode')
            ? $exception->getStatusCode()
            : 500;

        $response = [
            'success' => false,
            'message' => 'An error occurred.',
            'error' => $exception->getMessage() ?: 'Internal server error.'
        ];

        // Include detailed error info only in debug mode
        if (config('app.debug')) {
            $response['debug'] = [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => collect($exception->getTrace())->take(5)->toArray()
            ];
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Convert an authentication exception into a response.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'error' => 'You must be logged in to access this resource.'
            ], 401);
        }

        return redirect()->guest(route('login'));
    }
}
