<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RetryQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retry:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move failed jobs into the queue';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('queue:retry', [
            'id' => 'all'
        ]);
    }
}
