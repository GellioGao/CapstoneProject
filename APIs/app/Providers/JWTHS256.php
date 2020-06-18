<?php

namespace App\Providers;

use App\Models\User;
use App\Contracts\ILogger;
use App\Contracts\ITokenEncoder;
use App\Contracts\ITokenDecoder;

use Firebase\JWT\JWT as JWTProvider;

class JWTHS256 extends RecordableProvider implements ITokenEncoder, ITokenDecoder
{
    private const KeyOption = 'HS256';
    private string $Key;

    public function __construct(ILogger $logger)
    {
        parent::__construct($logger);
        $this->Key = config('app.jwt_key');
    }

    public function decode($jwt): array
    {
        $decoded = JWTProvider::decode($jwt, $this->Key, [JWTHS256::KeyOption]);
        return (array) $decoded;
    }

    // create a JWT with the user's details as the data attribute
    public function encode(array $tokenData)
    {
        $token = [
            "iss" => config('app.jwt_iss'),
            "aud" => config('app.jwt_aud'),
            "iat" => $iat = (time() - config('app.jwt_iat')),
            "nbf" => $iat + config('app.jwt_nbf'),
            "exp" => $iat + config('app.jwt_exp'),
            "data" => $tokenData
        ];

        // return the login token
        $jwt = JWTProvider::encode($token, $this->Key);
        return $jwt;
    }
}
