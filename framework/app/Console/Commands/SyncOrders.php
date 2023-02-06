<?php

namespace App\Console\Commands;

use App\Contracts\SyncOrdersProcessorInterface;
use Illuminate\Console\Command;

/**
 * Class SyncOrdersProcessor
 * @package App\Console\Commands
 */
class SyncOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:sync-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync orders with GCP database';

    /**
     * @var SyncOrdersProcessorInterface
     */
    private $syncOrdersProcessor;

    /**
     * @param SyncOrdersProcessorInterface $syncOrdersProcessor
     */
    public function __construct(SyncOrdersProcessorInterface $syncOrdersProcessor)
    {
        parent::__construct();

        $this->syncOrdersProcessor = $syncOrdersProcessor;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (isProduction()) {
            $this->syncOrdersProcessor->syncOrders();
        }
    }
}
