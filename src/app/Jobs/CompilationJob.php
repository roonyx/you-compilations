<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Compilations\CompilationService;

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
     * @var array
     */
    public $tags = [];

    /**
     * @var CompilationService
     */
    protected $service;

    /**
     * CompilationJob constructor.
     * @param int $userId
     * @param array $tags
     */
    public function __construct(int $userId, array $tags)
    {
        $this->userId = $userId;
        $this->tags = $tags;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->service = app(CompilationService::class);

        $this->service->compilation($this->userId, $this->tags);
    }
}
