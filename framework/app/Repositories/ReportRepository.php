<?php

namespace App\Repositories;

use App\Assets\DateRange;
use App\Contracts\CountriesServiceInterface;
use App\Models\Order\Order;
use Illuminate\Support\Facades\DB;

/**
 * Class ReportRepository
 * @package App\Repositories
 */
class ReportRepository
{
    /**
     * @param DateRange $range
     *
     * @return array
     */
    public function getCountrySales(DateRange $range): array
    {
        /** @var CountriesServiceInterface $countriesService */
        $countriesService = app(CountriesServiceInterface::class);

        $sql = "select DATE_FORMAT(`O`.`created_at`, '%m') as `month`, `B`.`country`, COUNT(`O`.`id`) as `sales`, SUM(`O`.`total`) as `amount`
                from `orders` as `O`
                    inner join `order_billing` as `B` ON `B`.`orderId` = `O`.`id` 
                where 
                    `O`.`status` = ? and
                    `O`.`discount` = 0 and
                    `O`.`created_at` >= ? and
                    `O`.`created_at` < ?
                group by `month`, `B`.`country`
                order by `month`, `B`.`country`";


        $result = DB::select($sql, [Order::STATUS_COMPLETED, $range->startDate, $range->endDate]);

        $countries = [];
        $data      = [];

        foreach ($result as $row) {
            $month = (int)$row->month;
            if (!isset($data[$month])) {
                $data[$month] = [
                    'month'     => getMonthName($month),
                    'sales'     => 0,
                    'amount'    => 0,
                    'countries' => []
                ];
            }

            $countryName = 'No country';

            if ($row->country) {
                if (!isset($countries[$row->country])) {
                    $country                  = $countriesService->getCountryByCode($row->country);
                    $countries[$row->country] = $country ? $country->getName() : $row->country;
                }

                $countryName = $countries[$row->country];
            }

            $data[$month]['countries'][] = [
                'name'   => $countryName,
                'sales'  => $row->sales,
                'amount' => $row->amount,
            ];
        }

        foreach ($data as &$info) {
            usort($info['countries'], function ($a, $b) {
                if ($a['amount'] < $b['amount']) {
                    return 1;
                } elseif ($a['amount'] > $b['amount']) {
                    return -1;
                } else {
                    return 0;
                }
            });

            $sales  = 0;
            $amount = 0;
            foreach ($info['countries'] as &$country) {
                $sales  += $country['sales'];
                $amount += $country['amount'];

                $country['amount'] = toFloatAmount($country['amount']);
            }

            $info['sales']  = $sales;
            $info['amount'] = toFloatAmount($amount);
        }

        return $data;
    }

    /**
     * @param DateRange $range
     *
     * @return array
     */
    public function getBestSellers(DateRange $range): array
    {
        $sql = "select `I`.`name` as `product`, COUNT(`I`.`id`) as `sales`, SUM(`I`.`total`) as `amount`
                from `order_items` as `I`
                inner join `orders` as `O` on `O`.`id` = `I`.`orderId`
                where 
                    `O`.`status` = ? and
                    `O`.`discount` = 0 and
                    `O`.`created_at` >= ? and
                    `O`.`created_at` < ?
                group by `I`.`name`
                order by `sales` desc
                limit 0, 5";

        $result = DB::select($sql, [Order::STATUS_COMPLETED, $range->startDate, $range->endDate]);

        $data = [];

        foreach ($result as $row) {
            $data[] = [
                'product' => $row->product,
                'sales'   => $row->sales,
                'amount'  => toFloatAmount($row->amount)
            ];
        }

        return $data;
    }

    /**
     * @param DateRange $range
     *
     * @return array
     */
    public function getCustomersAnalytics(DateRange $range): array
    {
        $sql = "select `x`.`userId`, `x`.`monthOrders`, count(`o`.`id`) as `previousOrders`
                from (
                    select `userId`, count(`id`) as `monthOrders`
                    from `orders`
                    where 
                      `status` = ? and
                      `discount` = 0 and
                      `created_at` >= ? and 
                      `created_at` <= ?
                    group by `userId`
                ) as `x`
                    left join `orders` as `o` on `o`.`userId` = `x`.`userId` and `status` = ? and `o`.`created_at` < ?
                group by `x`.`userId`, `x`.`monthOrders`";

        $result = DB::select($sql, [Order::STATUS_COMPLETED, $range->startDate, $range->endDate, Order::STATUS_COMPLETED, $range->startDate]);

        $totalCustomers     = count($result);
        $newCustomers       = 0;
        $returningCustomers = 0;

        foreach ($result as $row) {
            if ($row->previousOrders > 0) {
                $returningCustomers++;
            } else {
                $newCustomers++;
            }
        }

        return [
            'total'     => $totalCustomers,
            'new'       => $newCustomers,
            'returning' => $returningCustomers,
        ];
    }
}