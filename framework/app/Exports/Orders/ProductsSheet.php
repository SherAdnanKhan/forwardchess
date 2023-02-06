<?php

namespace App\Exports\Orders;

use App\Models\Order\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductsSheet implements FromCollection, WithHeadings, WithTitle
{
    use PeriodTrait;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Item::query()
            ->select([
                'order_items.orderId',
                'order_items.quantity',
                'order_items.unitPrice',
                'order_items.total',
                'order_items.name',
                'products.sku',
                'products.price',
            ])
            ->join('orders', 'orders.id', '=', 'order_items.orderId')
            ->leftJoin('products', 'products.id', '=', 'order_items.productId')
            ->where('orders.created_at', '>=', $this->startDay)
            ->where('orders.created_at', '<=', $this->endDay)
            ->get()
            ->map(function ($item) {
                return [
                    'Order ID'   => $item->orderId,
                    'Quantity'   => $item->quantity,
                    'Unit price' => $item->unitPrice,
                    'Total'      => $item->total,
                    'Name'       => $item->name,
                    'SKU'        => $item->sku,
                    'List price' => toFloatAmount($item->price),
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
        return 'Products';
    }
}