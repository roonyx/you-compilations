<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Compilations\AuthorService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Users\UserRepository;
use App\Repositories\Compilations\TagRepository;
use App\Repositories\Compilations\CompilationRepository;
use App\Repositories\Compilations\CompilationLogRepository;

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
        \Schema::defaultStringLength(191);
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

        $this->app->singleton(CompilationRepository::class, function () {
            return new CompilationRepository();
        });

        $this->app->singleton(CompilationLogRepository::class, function () {
            return new CompilationLogRepository();
        });
    }
}
