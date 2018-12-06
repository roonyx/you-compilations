<?php
/**
 * File: CompilationLogRepository.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-12
 * Copyright Roonyx.tech (c) 2018
 */

declare(strict_types=1);

namespace App\Repositories\Compilations;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Compilations\CompilationLog;

/**
 * Class CompilationLogRepository
 * @package Repositories\Compilations
 */
class CompilationLogRepository
{
    /**
     * CompilationLogRepository constructor.
     */
    public function __construct()
    {
        CompilationLog::saving(function (CompilationLog $model) {
            if (empty($model->status)) {
                $model->status = CompilationLog::WAITING;
            }
            return true;
        });
    }

    /**
     * @param User $user
     * @param array $attributes
     * @return CompilationLog
     * @throws \Throwable
     */
    public function create(User $user, array $attributes = []): CompilationLog
    {
        /** @var CompilationLog $model */
        $model = CompilationLog::make($attributes);

        $model->user()->associate($user);
        $model->saveOrFail();

        return $model;
    }

    /**
     * @param User $user
     * @param Carbon $date
     * @return bool
     */
    public function isStandingInQueue(User $user, Carbon $date): bool
    {
        $compilationLog = $this->findByDate($user, $date);

        if ($compilationLog) {
            return $compilationLog->isWaiting();
        }

        return false;
    }

    /**
     * @param User $user
     * @param Carbon $date
     * @return bool
     */
    public function isComplete(User $user, Carbon $date): bool
    {
        $compilationLog = $this->findByDate($user, $date);

        if ($compilationLog) {
            return $compilationLog->isSuccessfully();
        }

        return false;
    }

    /**
     * @param User $user
     * @param Carbon $date
     * @return CompilationLog
     */
    public function findByDate(User $user, Carbon $date): ?CompilationLog
    {
        return CompilationLog::query()
            ->where('user_id', '=', $user->getKey())
            ->whereBetween('created_at', [
                (clone $date)->setTime(0, 0),
                (clone  $date)->setTime(23, 59, 59),
            ])
            ->first();
    }
}
