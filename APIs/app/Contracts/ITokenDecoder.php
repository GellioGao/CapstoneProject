<?php

namespace App\Contracts;

interface ITokenDecoder
{
    /**
     * Decode the token by pass a token string, and return the token data.
     *
     * @param  string  $token
     * @return array
     */
    function decode($token): array;
}
