<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Cookie;



class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $this->authService->register($validated);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    /**
     * Login user (Passport token)
     */

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $data = $this->authService->login(
            $validated['email'],
            $validated['password']
        );

        $cookie = cookie(
            'access_token',
            $data['token'],
            60 * 24,
            '/',
            null,
            false,
            true,
            false,
            'Lax'
        );

        return response()
            ->json([
                'message' => 'success',
                'user' => $data['user'],
            ])
            ->withCookie($cookie);

    }


    /**
     * Logout user
     */

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()
            ->json(['message' => 'Logged out successfully'])
            ->withCookie(Cookie::forget('access_token'));
    }



    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
