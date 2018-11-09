<?php
/**
 * File: UserRepository.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Repositories\Users;

use Carbon\Carbon;
use App\Models\User;
use App\Repositories\RepositoryInterface;

/**
 * Class UserRepository
 * @package App\Repositories\Users
 */
class UserRepository implements RepositoryInterface
{
    /**
     * @param array $attributes
     * @return User
     * @throws \Exception
     */
    public function create(array $attributes): User
    {
        $user = User::create($attributes);
        return $user;
    }

    /**
     * @param User $user
     * @param array $attributes
     */
    public function update($user, array $attributes): void
    {
        $user->update($attributes);
    }

    /**
     * @param User $user
     */
    public function verifyEmail(User $user): void
    {
        $user->email_verified_at = Carbon::now();
        $user->save();
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function get(int $id): ?User
    {
        return User::whereKey($id)->first();
    }

    /**
     * @param int $id
     * @return User
     */
    public function find(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email): User
    {
        return User::query()->where('email', $email)->firstOrFail();
    }

    /**
     * @return User
     */
    public function getModel(): User
    {
        return new User();
    }
}
