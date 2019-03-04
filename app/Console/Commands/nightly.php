<?php

namespace App\Console\Commands;

use Log;
use App\Http\Controllers\Misc\CronController;
use Illuminate\Console\Command;

class nightly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:nightly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nightly batch job';

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
     */
    public function handle()
    {
        Log::info('Nightlybatch job');
        CronController::nightly();
    }
}
