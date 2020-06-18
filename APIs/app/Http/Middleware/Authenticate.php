<?php

namespace App\Http\Middleware;

use App\Exceptions\{InvalidTokenException, TokenMissingException};
use App\Http\Resources\ResponseResources\BadResponseResource;

use Closure;

use Illuminate\Contracts\Auth\Factory as Auth;
use Throwable;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            if (is_null($request->user())) {
                return $this->unauthorizedResponse(null);
            }
        } catch (TokenMissingException $tme) {
            return $this->unauthorizedResponse($tme);
        } catch (InvalidTokenException $ite) {
            return $this->unauthorizedResponse($ite);
        }

        return $next($request);
    }

    private function unauthorizedResponse(?Throwable $ex)
    {
        $message = null;
        if (!\is_null($ex)) {
            $message = $ex->getMessage();
        }
        return response(
            new BadResponseResource(
                FAILED_RESULT_RESPONSE,
                DENIED_ACCESS_RESPONSE,
                UNAUTHORIZED_MESSAGE,
                $message
            ),
            401
        );
    }
}
