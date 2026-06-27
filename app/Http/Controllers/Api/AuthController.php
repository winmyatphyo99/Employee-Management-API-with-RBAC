<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class AuthController extends BaseApiController
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return $this->created([
            'user'  => UserResource::make($result->user),
            'token' => $result->token,
        ], 'User registered successfully');
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        return $this->success([
            'user'  => UserResource::make($result->user),
            'token' => $result->token,
        ], 'Login successful');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->success(null, 'Logout successful');
    }

    public function me(Request $request)
    {
        return $this->success(
            UserResource::make($request->user()->load('roles')),
            'User profile fetched successfully'
        );
    }
}
