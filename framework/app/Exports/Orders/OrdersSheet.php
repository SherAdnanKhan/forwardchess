<?php

namespace App\Exports\Orders;

use App\Assets\DateRange;
use App\Models\Order\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrdersSheet implements FromCollection, WithHeadings, WithTitle
{
    use PeriodTrait;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this
            ->orderRepository
            ->get(['period' => DateRange::make($this->startDay, $this->endDay)])
            ->map(function (Order $order) {
                return [
                    'ID'             => $order->id,
                    'Date'           => $order->getCreatedAtFormatted(),
                    'Status'         => $order->status,
                    'Reference'      => $order->refNo,
                    'Customer email' => $order->email,
                    'Customer name'  => $order->fullName,
                    'Coupon'         => $order->coupon,
                    'Discount'       => (string)$order->discount,
                    'Taxes'          => (string)$order->taxAmount,
                    'Total'          => (string)$order->total,

                ];
            });
    }

    public function headings(): array
    {
        return $this->collection()->count() ? array_keys($this->collection()->first()) : [];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Orders';
    }
}