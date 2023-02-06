<?php

use App\Models\Order\Order;
use App\Models\Order\Item;
use App\Models\Product\Product;
use App\Models\TaxRate;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    /**
     * @var array
     */
    private $users;

    /**
     * @var array
     */
    private $products;

    /**
     * @var array
     */
    private $taxRates;

    public function __construct()
    {
        $this->users = [];
        foreach (User::all() as $user) {
            $this->users[$user->lastId] = $user->id;
        }

        $this->products = [];
        foreach (Product::all() as $product) {
            $this->products[$product->lastId] = $product->id;
        }

        $this->taxRates = [];
        foreach (TaxRate::all() as $index => $taxRate) {
            $this->taxRates[$index + 1] = $taxRate->toArray();
        }

        Order::$makePricesInteger = true;
        Item::$makePricesInteger = true;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->truncate();
        DB::table('order_billing')->truncate();
        DB::table('order_payment')->truncate();
        DB::table('order_items')->truncate();

        $orderColumns = [
            'userId',
            'paymentMethod',
            'name',
            'ipAddress',
            'userAgent',
            'completedDate',
            'paidDate',
            'allowDownload',
            'currency',
            'discount',
            'tax',
            'total'
        ];

        $billingColumns = [
            'billingFirstName' => 'firstName',
            'billingLastName'  => 'lastName',
            'billingCompany'   => 'company',
            'billingAddress1'  => 'address1',
            'billingAddress2'  => 'address2',
            'billingCity'      => 'city',
            'billingState'     => 'state',
            'billingPostCode'  => 'postcode',
            'billingCountry'   => 'country',
            'billingEmail'     => 'email',
            'billingPhone'     => 'phone'
        ];

        $paymentColumns = [
            'paymentFirstName'      => 'firstName',
            'paymentLastName'       => 'lastName',
            'paymentAddress'        => 'email',
            'paymentTransactionFee' => 'feeAmount',
            'paymentTransactionKey' => 'transactionKey'
        ];

        $statuses = [
            'wc-pending'    => 'pending',
            'wc-processing' => 'processing',
            'wc-completed'  => 'completed',
            'wc-cancelled'  => 'cancelled',
            'wc-refunded'   => 'refunded',
            'wc-on-hold'    => 'on-hold',
        ];


        $results = DB::connection('old')->table('fc_posts')->where('post_type', '=', 'shop_order')->get();

        foreach ($results as $oneResult) {
            $meta = DB::connection('old')->table('fc_postmeta')->where('post_id', '=', $oneResult->ID)->get();

            $metaColumns = [
                '_customer_user'                => 'userId',
                '_payment_method'               => 'paymentMethod',
                '_customer_ip_address'          => 'ipAddress',
                '_customer_user_agent'          => 'userAgent',
                '_completed_date'               => 'completedDate',
                '_paid_date'                    => 'paidDate',
                '_download_permissions_granted' => 'allowDownload',
                '_order_currency'               => 'currency',
                '_cart_discount'                => 'discount',
                '_cart_discount_tax'            => 'discountTax',
                '_order_total'                  => 'total',
                '_billing_first_name'           => 'billingFirstName',
                '_billing_last_name'            => 'billingLastName',
                '_billing_company'              => 'billingCompany',
                '_billing_address_1'            => 'billingAddress1',
                '_billing_address_2'            => 'billingAddress2',
                '_billing_city'                 => 'billingCity',
                '_billing_state'                => 'billingState',
                '_billing_postcode'             => 'billingPostCode',
                '_billing_country'              => 'billingCountry',
                '_billing_email'                => 'billingEmail',
                '_billing_phone'                => 'billingPhone',
                '_paypal_status'                => 'paymentStatus',
                'Payment type'                  => 'paymentType',
                'Payer first name'              => 'paymentFirstName',
                'Payer last name'               => 'paymentLastName',
                'Payer PayPal address'          => 'paymentAddress',
                'PayPal Transaction Fee'        => 'paymentTransactionFee',
                'taxamo_transaction_key'        => 'paymentTransactionKey',
                '_eu_vat_data'                  => 'paymentEuVatData',
                '_eu_vat_evidence'              => 'paymentEuVatEvidence',
            ];

            $attributes = [];
            foreach ($meta as $item) {
                if (array_key_exists($item->meta_key, $metaColumns)) {
                    $attributes[$metaColumns[$item->meta_key]] = $item->meta_value;
                }
            }

            $orderAttributes   = [];
            $billingAttributes = [];
            $paymentAttributes = [];

            foreach ($orderColumns as $column) {
                if (!empty($attributes[$column])) {
                    $value = $attributes[$column];

                    switch ($column) {
//                        case 'discount':
//                        case 'discountTax':
//                        case 'tax':
//                        case 'total':
//                            $value = $this->formatPrice($value);
//                            break;

                        case 'allowDownload':
                            $value = ($value === 'yes') ? 1 : 0;
                            break;

                        case 'userId':
                            $value = isset($this->users[$value]) ? $this->users[$value] : null;
                            break;
                    }

                    $orderAttributes[$column] = $value;
                }
            }

            foreach ($billingColumns as $column => $finalColumn) {
                if (!empty($attributes[$column])) {
                    $billingAttributes[$finalColumn] = $attributes[$column];
                }
            }

            foreach ($paymentColumns as $column => $finalColumn) {
                if (!empty($attributes[$column])) {
                    $paymentAttributes[$finalColumn] = $attributes[$column];
                }
            }

            $orderAttributes['status']     = $statuses[$oneResult->post_status];
            $orderAttributes['created_at'] = $oneResult->post_date;
            $orderAttributes['lastId']     = $oneResult->ID;

            $order = Order::create($orderAttributes);

            if (!empty($billingAttributes)) {
                $order->billing()->create($billingAttributes);
            }

            if (!empty($paymentAttributes)) {
                $order->payment()->create($paymentAttributes);
            }

            $this->importItems($order);
        }
    }

    private function importItems(Order $order)
    {
        $results = DB::connection('old')->table('fc_woocommerce_order_items')->where('order_id', '=', $order->lastId)->get();

        $orderUpdates = [];
        $subTotal     = 0;
        foreach ($results as $oneResult) {
            $meta = DB::connection('old')->table('fc_woocommerce_order_itemmeta')->where('order_item_id', '=', $oneResult->order_item_id)->get();

            $metaColumns = [
                '_line_subtotal' => 'total',
                '_product_id'    => 'productId',
                '_qty'           => 'quantity',
                'coupon_data'    => 'coupon',
                'rate_id'        => 'taxRateId',
                'tax_amount'     => 'taxAmount',
            ];
//
            $attributes = [];
            foreach ($meta as $item) {
                if (array_key_exists($item->meta_key, $metaColumns)) {
                    $attributes[$metaColumns[$item->meta_key]] = $item->meta_value;
                }
            }

            $item = array_merge([
                'name' => $oneResult->order_item_name
            ], $attributes);

            $type = $oneResult->order_item_type;

            if ($type === 'tax') {
                $taxRateId = $item['taxRateId'];
                if (isset($this->taxRates[$taxRateId]) && !empty($taxRate = $this->taxRates[$taxRateId])) {
                    $orderUpdates['taxRateCountry'] = $taxRate['country'];
                    $orderUpdates['taxRateAmount']  = $taxRate['rate'];
                    $orderUpdates['taxAmount']      = $item['taxAmount'];
                }
                continue;
            }

            if ($type === 'coupon') {
                $orderUpdates['coupon'] = $item['name'];
                continue;
            }

            if (!empty($item['productId'])) {
                $item['productId'] = isset($this->products[$item['productId']]) ? $this->products[$item['productId']] : null;
            }

            $item['unitPrice'] = $item['total'] / (int)$item['quantity'];
            $subTotal          += $item['total'];

            $order->items()->create($item);
        }

        $orderUpdates['subTotal'] = $subTotal;

        $order->update($orderUpdates);
    }

    private function formatPrice($price)
    {
        return number_format($price * 100, 0, '', '');
    }
}
