<?php

namespace App\Console\Commands;

use App\Contracts\OrdersProcessorInterface;
use Illuminate\Console\Command;

/**
 * Class OrdersProcessor
 * @package App\Console\Commands
 */
class ProcessOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the unpaid orders';

    /**
     * @var OrdersProcessorInterface
     */
    private $ordersProcessor;

    /**
     * ProcessOrders constructor.
     *
     * @param OrdersProcessorInterface $ordersProcessor
     */
    public function __construct(OrdersProcessorInterface $ordersProcessor)
    {
        parent::__construct();

        $this->ordersProcessor = $ordersProcessor;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->ordersProcessor->processPendingOrders();
    }
}
