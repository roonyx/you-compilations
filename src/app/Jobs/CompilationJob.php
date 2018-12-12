<?php

declare(strict_types=1);

namespace App\Jobs;

use Carbon\Carbon;
use Psr\Log\LoggerInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Compilations\CompilationLog;
use App\Repositories\Users\UserRepository;
use App\Services\Compilations\CompilationService;
use App\Repositories\Compilations\CompilationLogRepository;

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
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var CompilationService
     */
    protected $compilationService;
    /**
     * @var CompilationLogRepository
     */
    protected $compilationLogRepository;

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
     * @throws \Throwable
     */
    public function handle()
    {
        try {
            $this->logger = app(LoggerInterface::class);
            $this->userRepository = app(UserRepository::class);
            $this->compilationService = app(CompilationService::class);
            $this->compilationLogRepository = app(CompilationLogRepository::class);

            $user = $this->userRepository->get($this->userId);

            if ($this->compilationLogRepository->findByDate($user, Carbon::now())) {
                $this->delete();
                throw new \Exception('Compilation job is running.');
            }

            $log = $this->compilationLogRepository->create($user);

            try {
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
                $log->status = CompilationLog::SUCCESS;
            } catch (\Exception $exception) {
                $log->status = CompilationLog::FAILED;
                $log->description = $exception->getMessage();
                $this->logger->error(\parseException($exception));
            }

            $log->save();
        } catch (\Exception $exception) {
            $this->logger->error(\parseException($exception));
        }
    }
}
