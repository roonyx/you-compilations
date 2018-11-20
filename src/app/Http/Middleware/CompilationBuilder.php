<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Carbon\Carbon;
use App\Models\User;
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
    public function handle($request, \Closure $next)
    {
        /** @var User $user */
        $user = \Auth::user();

        if ($user
            && $this->isNeedCompilation($user)
            && !$user->inQueueInProcess()) {
            $tags = $user->tags->pluck('name')->toArray();
            CompilationJob::dispatch($user->getKey(), $tags)->delay(
                Carbon::now()
            );
        }

        return $next($request);
    }

    /**
     * @param User $user
     * @return bool
     */
    protected function isNeedCompilation(User $user)
    {
        return !$user->compilations()->exists() && $user->tags()->exists();
    }
}
