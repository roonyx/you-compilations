<?php

declare(strict_types=1);

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Compilations\CompilationService;
use App\Repositories\Users\UserRepository;

/**
 * Class CompilationJob
 * @package App\Jobs
 */
class CompilationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public $userId;

    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var CompilationService
     */
    protected $compilationService;

    /**
     * CompilationJob constructor.
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $this->userRepository = app(UserRepository::class);
        $this->compilationService = app(CompilationService::class);

        if ($user = $this->userRepository->get($this->userId)) {
            $tagsNamed = $user
                ->tags
                ->pluck('name')
                ->toArray();
            $this
                ->compilationService
                ->compilation($this->userId, $tagsNamed);

            // Start to generate an compilation videos
            // on the next day 15:00 hours
            self::dispatch($this->userId)->delay(
                Carbon::now()
                    ->addDay(1)
                    ->setTime(15, 0)
            );
        }
    }
}
