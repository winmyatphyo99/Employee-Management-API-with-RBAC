<?php

namespace App\DTOs\Auth;

use App\Models\User;

class AuthResponseData
{
    public function __construct(
        public User $user,
        public string $token
    ) {}

    public static function from(User $user, string $token): self
    {
        return new self($user, $token);
    }
}
