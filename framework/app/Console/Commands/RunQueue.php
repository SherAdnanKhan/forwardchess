<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RunQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the artisan queue';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        Log::debug('queue:work');

        Artisan::call('queue:work', [
            '--stop-when-empty' => true
        ]);
    }
}
