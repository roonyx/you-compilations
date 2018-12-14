<?php

namespace App\Console\Commands;

use App\Services\Compilations\AuthorService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class CreatingAuthors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:authors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param LoggerInterface $logger
     * @return mixed
     * @throws \Exception
     */
    public function handle(LoggerInterface $logger)
    {
        $logger->info('Start authors parsing...');

        try {
            AuthorService::parse();
        } catch (\Exception $exception) {
            $logger->error(\parseException($exception));
        }

        $logger->info('Authors parsed...');
    }
}
