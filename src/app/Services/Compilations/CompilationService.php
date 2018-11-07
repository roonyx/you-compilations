<?php
/**
 * File: CompilationService.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Services\Compilations;

use App\Models\User;
use Repositories\Users\UserRepository;

/**
 * Class CompilationService
 * @package Services\Compilations
 */
class CompilationService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $repository)
    {
        $this->userRepository = $repository;
    }

    /**
     * Create a video compilation for user
     *
     * @param int $userId
     * @param array $tags
     * @return bool
     */
    public function compilation(int $userId, array $tags): bool
    {
        $user = $this->userRepository->find($userId);



        $result = \Youtube::searchVideos('Взлом игр и программирование трейнеров');
    }
}
