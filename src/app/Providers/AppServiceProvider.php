<?php

declare(strict_types=1);

namespace App\Providers;

use Repositories\Users\TagRepository;
use Repositories\Users\UserRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->singleton(UserRepository::class, function () {
            return new UserRepository();
        });

        $this->app->singleton(TagRepository::class, function () {
            return new TagRepository();
        });
    }
}
