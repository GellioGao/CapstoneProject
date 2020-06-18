<?php

namespace App\Contracts;

interface ITokenEncoder
{
    /**
     * Encode the token by pass a token data.
     *
     * @param  array  $tokenData
     * @return string
     */
    function encode(array $tokenData);
}