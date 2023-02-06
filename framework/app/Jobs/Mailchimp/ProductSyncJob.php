<?php

namespace App\Jobs\Mailchimp;

use App\Assets\MailChimp\ProductAction;
use App\Common\CrudActions;
use App\Contracts\MailChimpServiceInterface;
use App\Jobs\JobSettings;
use App\Models\Product\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class ProductSyncJob
 * @package App\Jobs\Mailchimp
 */
class ProductSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use JobSettings;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var string
     */
    private $action;

    /**
     * ProductSyncJob constructor.
     *
     * @param Product $product
     * @param string  $action
     */
    public function __construct(Product $product, string $action = CrudActions::ACTION_UPDATED)
    {
        $this->product = $product;
        $this->action  = $action;
    }

    /**
     * Execute the job.
     *
     * @param MailChimpServiceInterface $mailChimpService
     *
     * @throws \Exception
     */
    public function handle(MailChimpServiceInterface $mailChimpService)
    {
        try {
            $mailChimpService->syncProduct(ProductAction::make($this->action, $this->product));
        } catch (\Exception $e) {
            $ignoreCodes = [409, 503];

            if (!in_array($e->getCode(), $ignoreCodes)) {
                $this->onFail($e);
            }
        }
    }
}
