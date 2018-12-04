<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Carbon\Carbon;
use App\Models\User;
use App\Jobs\CompilationJob;
use App\Repositories\Compilations\CompilationLogRepository;

/**
 * Class Compilation
 * @package App\Http\Middleware
 */
class CompilationBuilder
{
    /**
     * @var CompilationLogRepository
     */
    protected $repository;

    /**
     * CompilationBuilder constructor.
     *
     * @param CompilationLogRepository $repository
     */
    public function __construct(CompilationLogRepository $repository)
    {
        $this->repository = $repository;
    }

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
            && !$this->inQueueForProcessing($user)) {
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

    /**
     * @param User $user
     * @return bool
     */
    public function inQueueForProcessing(User $user): bool
    {
        $date = Carbon::now();
        return $this->repository->isStandingInQueue($user, $date)
            ?: $this->repository->isComplete($user, $date);
    }
}
