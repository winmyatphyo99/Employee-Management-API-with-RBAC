<?php

namespace App\Services;

use App\Models\User;
use App\DTOs\Auth\AuthResponseData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\HasApiTokens;

class AuthService
{
    public function register(array $data): AuthResponseData
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $roleId = \App\Models\Role::where('name', 'Employee')->value('id');

        if ($roleId) {
            $user->roles()->attach($roleId);
        }

        $user->load('roles');

        $token = $user->createToken('auth_token')->accessToken;

        return AuthResponseData::from($user, $token);
    }

    public function login(array $data): AuthResponseData
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.']
            ]);
        }

        $user->load('roles');

        $token = $user->createToken('auth_token')->accessToken;

        return AuthResponseData::from($user, $token);
    }

    public function logout(User $user): bool
    {
        $user->token()->revoke();
        return true;
    }
}
