<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class AuthController extends BaseApiController
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->validated());

        return $this->successResponse([
            'user' => new UserResource($data['user']),
            'token' => $data['token'],
        ], 'User registered successfully.', 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        return $this->successResponse([
            'user' => new UserResource($data['user']),
            'token' => $data['token'],
        ], 'Login successful.');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->successResponse(
            null,
            'Logout successful.'
        );
    }

    public function me(Request $request)
    {
        return $this->successResponse(
            new UserResource($request->user()->load('roles'))
        );
    }
}
