<?php

namespace App\Repositories\Order;

use App\Assets\PlaceOrder;
use App\Assets\SortBy;
use App\Builders\OrderBuilder;
use App\Contracts\BlockedProductsInterface;
use App\Contracts\CacheStorageInterface;
use App\Exceptions\CommonException;
use App\Models\AbstractModel;
use App\Models\Gift;
use App\Models\Numbers;
use App\Models\Order\Item as OrderItem;
use App\Models\Order\Order;
use App\Models\User\User;
use App\Repositories\AbstractModelRepository;
use App\Repositories\GiftRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderRepository
 * @package App\Repositories\Order
 */
class OrderRepository extends AbstractModelRepository
{
    use Numbers;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @var BlockedProductsInterface
     */
    private BlockedProductsInterface $blockedProducts;

    /**
     * @param AbstractModel            $resource
     * @param CacheStorageInterface    $cache
     * @param ProductRepository        $productRepository
     * @param BlockedProductsInterface $blockedProducts
     */
    public function __construct(AbstractModel $resource, CacheStorageInterface $cache, ProductRepository $productRepository, BlockedProductsInterface $blockedProducts)
    {
        parent::__construct($resource, $cache);

        $this->productRepository = $productRepository;
        $this->blockedProducts   = $blockedProducts;
    }

    /**
     * @param array       $search
     * @param SortBy|null $sortBy
     *
     * @return Builder
     */
    public function getBuilder(array $search = [], SortBy $sortBy = null): Builder
    {
        $search['trashIncluded'] = $this->isTrashedIncludedInSearch();
        $search['sortBy']        = $sortBy;

        return OrderBuilder::make($search)->init();
    }

    /**
     * @param PlaceOrder $data
     *
     * @return Order|null
     * @throws CommonException
     */
    public function placeOrder(PlaceOrder $data): ?Order
    {
        $orderDetails   = $data->getDetails();
        $billingDetails = $data->getBilling();
        $items          = $data->getItems();

        if (empty($orderDetails) || empty($billingDetails) || empty($items)) {
            $this->invalidFormDataException();
        }

        try {
            DB::beginTransaction();

            /** @var Order $order */
            $order = $this->getResource()->create($orderDetails);

            if (!empty($billingDetails)) {
                $order->billing()->create($billingDetails);
            }

            if (!empty($paymentDetails)) {
                $order->payment()->create($paymentDetails);
            }

            $productIds = [];
            foreach ($items as $item) {
                $product = $item->getProduct();

                $orderItem = [
                    'type'             => OrderItem::TYPE_PRODUCT,
                    'productId'        => $product->id,
                    'name'             => $product->title,
                    'quantity'         => $item->getQuantity(),
                    'unitPrice'        => $product->sellPrice,
                    'total'            => $product->sellPrice * $item->getQuantity(),
                    'product'          => $product,
                    'isBundle'         => $product->isBundle,
                    'purchasedSection' => $item->getSection()
                ];

                $order->items()->create($orderItem);
                $productIds[] = $product->id;
                $product->fireEvent('resetting');

                if ($product->isBundle) {
                    foreach ($product->children as $child) {
                        $price = $product->hasDiscount()
                            ? $item->calculateDiscount($child->sellPrice, $item->getProduct()->discount)
                            : $child->sellPrice;

                        $bundleOrderItem = [
                            'type'             => OrderItem::TYPE_PRODUCT,
                            'productId'        => $child->id,
                            'name'             => $child->title,
                            'quantity'         => $item->getQuantity(),
                            'unitPrice'        => $price,
                            'total'            => $price * $item->getQuantity(),
                            'product'          => $child,
                            'purchasedSection' => $item->getSection()
                        ];

                        $order->items()->create($bundleOrderItem);
                        $productIds[] = $child->id;
                        $child->fireEvent('resetting');
                    }
                }
            }

            DB::update('UPDATE `products` SET `totalSales` = `totalSales` + 1 WHERE `id` IN (' . implode(', ', $productIds) . ')');

            if (!empty($gifts = $data->getGifts())) {
                $discount = $orderDetails['discount'];

                foreach ($gifts as $gift) {
                    $voucher = [
                        'giftId' => $gift->id,
                        'amount' => min($gift->amount, $discount)
                    ];

                    $order->vouchers()->create($voucher);

                    $discount -= $voucher['amount'];
                    $gift->setAmountAttribute($gift->amount - $voucher['amount'], false);
                    $gift->save();
                }
            }

            if (!empty($coupon = $data->getCoupon())) {
                $coupon->incrementUsages();
                $coupon->fireEvent('updating');
            }

            DB::commit();

            return $order->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dataNotSavedException();
            return null;
        }
    }

    /**
     * @param PlaceOrder $data
     *
     * @return AbstractModel
     * @throws CommonException
     */
    public function placeGiftOrder(PlaceOrder $data): AbstractModel
    {
        $orderDetails   = $data->getDetails();
        $billingDetails = $data->getBilling();
        $giftCard       = $data->getGiftCard();

        if (empty($orderDetails) || empty($billingDetails)) {
            $this->invalidFormDataException();
        }

        try {
            DB::beginTransaction();

            /** @var Order $order */
            $order = $this->getResource()->create($orderDetails);

            if (!empty($billingDetails)) {
                $order->billing()->create($billingDetails);
            }

            if (!empty($paymentDetails)) {
                $order->payment()->create($paymentDetails);
            }

            /** @var GiftRepository $giftRepository */
            $giftRepository = app(GiftRepository::class);

            /** @var Gift $giftModel */
            $giftModel = $giftRepository->store([
                'userId'        => $orderDetails['userId'],
                'amount'        => $giftCard->getAmount(),
                'friendEmail'   => $giftCard->getEmail(),
                'friendName'    => $giftCard->getName(),
                'friendMessage' => $giftCard->getMessage(),
                'orderId'       => $order->id
            ]);

            $orderItem = [
                'type'      => OrderItem::TYPE_GIFT,
                'productId' => $giftModel->id,
                'name'      => '$' . toFloatAmount($giftModel->amount) . ' Forward Chess gift card',
                'quantity'  => 1,
                'unitPrice' => $giftModel->amount,
                'total'     => $giftModel->amount
            ];

            $order->items()->create($orderItem);

            DB::commit();

            return $order->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e);
            $this->dataNotSavedException();
        }
    }

    /**
     * @param string $refNo
     *
     * @return string
     */
    public function getIdFromRefNo(string $refNo): ?string
    {
        /** @var Order $order */
        $order = $this->getResource();

        return $order->getIdFromRefNo($refNo);
    }

    /**
     * @param int $userId
     *
     * @return Order|null
     */
    public function getUserLastOrder(int $userId): ?Order
    {
        /** @var Order $order */
        $order = $this->getBuilder(['userId' => $userId], SortBy::make('created_at', 'desc'))->with(['billing'])->first();

        return $order;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function hasOrders(User $user): bool
    {
        $order = $this->getBuilder(['userId' => $user->id])->whereIn('status', ['completed', 'pending'])->first();

        return !empty($order);
    }

    /**
     * @param int $userId
     * @param int $productId
     *
     * @return bool
     */
    public function userHasPurchase(int $userId, int $productId): bool
    {
        // $query = 'SELECT `orders`.`id`
        //         FROM `orders`
        //             INNER JOIN `order_items` ON
        //                 `order_items`.`orderId` = `orders`.`id` AND
        //                 `order_items`.`type` = ? AND
        //                 `order_items`.`productId` = ?
        //         WHERE
        //             `orders`.`userId` = ? AND
        //             `orders`.`status` = ?
        //         GROUP BY `orders`.`id`
        //         LIMIT 0, 1;';

        // $result = DB::select($query, [Item::TYPE_PRODUCT, $productId, $userId, Order::STATUS_COMPLETED]);

        // return !empty($result);

        return $this->blockedProducts->hasProduct($productId);
    }
}
