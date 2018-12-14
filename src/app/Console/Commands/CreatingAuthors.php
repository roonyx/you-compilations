<?php

namespace App\Console\Commands;

use App\Services\Compilations\AuthorService;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $date = Carbon::parse('2018-12-13T11:40:00.000Z');
        $dateCurrent = Carbon::now();

        $interval = $dateCurrent->diffAsCarbonInterval($date);

        dd((string)$interval);
//        AuthorService::parse();
    }
}
