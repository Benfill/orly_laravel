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
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Force JSON response for ALL /api/* routes
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $exception);
        }

        // Also check if request expects JSON
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

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

        // Authorization Exception
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
                'error' => 'You do not have permission to access this resource.'
            ], 403);
        }

        // Model Not Found
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
                'error' => 'The requested resource does not exist.'
            ], 404);
        }

        // Route Not Found
        if ($exception instanceof RouteNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'error' => 'Authentication required to access this resource.'
            ], 401);
        }

        // Not Found
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint not found.',
                'error' => 'The requested endpoint does not exist.'
            ], 404);
        }

        // Method Not Allowed - THIS IS YOUR CURRENT ERROR
        if ($exception instanceof MethodNotAllowedHttpException) {
            $allowedMethods = $exception->getHeaders()['Allow'] ?? 'Unknown';
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed.',
                'error' => 'The ' . $request->method() . ' method is not supported for this route.',
                'allowed_methods' => $allowedMethods,
                'hint' => 'This route only accepts: ' . $allowedMethods
            ], 405);
        }

        // HTTP Exception
        if ($exception instanceof HttpException) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage() ?: 'HTTP error occurred.',
                'error' => $exception->getMessage()
            ], $exception->getStatusCode());
        }

        // Generic Exception
        $statusCode = method_exists($exception, 'getStatusCode')
            ? $exception->getStatusCode()
            : 500;

        $response = [
            'success' => false,
            'message' => 'An error occurred.',
            'error' => $exception->getMessage() ?: 'Internal server error.'
        ];

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
