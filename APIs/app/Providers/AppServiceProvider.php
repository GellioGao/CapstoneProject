<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(
            \App\Contracts\ITokenValidator::class,
            \App\Providers\TokenValidator::class
        );

        $this->app->singleton(
            \App\Contracts\ILogger::class,
            \App\Providers\Logger::class
        );

        $this->app->singleton(
            \App\Contracts\IArrayStructureChecker::class,
            \App\Providers\ArrayStructureChecker::class
        );

        $worker =  $this->app->make(JWTHS256::class);
        $this->app->singleton('App\Contracts\ITokenDecoder', function ($app) use ($worker) {
            return $worker;
        });
        $this->app->singleton('App\Contracts\ITokenEncoder', function ($app) use ($worker) {
            return $worker;
        });

        $this->app->bind(
            \App\Contracts\Repositories\IMemberRepository::class,
            \App\Repositories\MemberRepository::class
        );

        $this->app->when(\App\Repositories\MemberRepository::class)
            ->needs(\App\Contracts\IQueryable::class)
            ->give(\App\Models\Member::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
    }
}
