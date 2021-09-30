<?php

namespace App\Providers;

use App\Repository\AuthenticatedUser;
use App\Repository\LaravelAuthenticatedUser;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public array $bindings = [
        AuthenticatedUser::class => LaravelAuthenticatedUser::class,
    ];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Lib\LinkPreview\LinkPreviewInterface::class, \App\Lib\LinkPreview\LinkPreview::class);
        // $this->app->bind(\App\Lib\LinkPreview\LinkPreviewInterface::class, \App\Lib\LinkPreview\MockLinkPreview::class);
        $this->app->bind(AuthenticatedUser::class, LaravelAuthenticatedUser::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
