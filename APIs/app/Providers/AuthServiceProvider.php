<?php

namespace App\Providers;

use App\Exceptions\{InvalidTokenException, TokenMissingException};

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        $this->app['auth']->viaRequest('api', function ($request) {
            $validator = app('App\Contracts\ITokenValidator');
            $token = $request->header(AUTHORIZATION_HEADER);
            if (!$token) {
                throw new TokenMissingException();
            }
            $startingString = AUTHORIZATION_TYPE;
            $isStartsWithJWT = substr($token, 0, strlen($startingString)) === $startingString;
            
            $token = trim(str_replace(AUTHORIZATION_TYPE . ' ', '', $token));
            if (!$isStartsWithJWT || !$token) {
                throw new InvalidTokenException();
            }
            $token = trim(str_replace(AUTHORIZATION_TYPE . ' ', '', $token));
            
            return $validator->validate($token);
        });
    }
}
