<?php

namespace App\Contracts;

use App\Models\User;

interface ITokenValidator
{
    /**
     * Get the user by pass a token.
     *
     * @param  string  $token
     * @return App\Models\User|null
     */
    function validate(string $token): ?User;
}
