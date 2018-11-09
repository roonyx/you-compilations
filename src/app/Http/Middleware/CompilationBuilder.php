<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Jobs\CompilationJob;

/**
 * Class Compilation
 * @package App\Http\Middleware
 */
class CompilationBuilder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($user = \Auth::user()) {
            if (!$user->compilations()->exists() && $user->tags()->exists()) {
                $tags = $user->tags->pluck('name')->toArray();
                CompilationJob::dispatch($user->getKey(), $tags)->delay(
                    Carbon::now()->addMinute(1)
                );
            }
        }
        return $next($request);
    }
}
