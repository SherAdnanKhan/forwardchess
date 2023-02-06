<?php

namespace App\Http\Controllers\Site;

use App\Exports\Orders\OrdersExport;
use App\Exports\ProductsExport;
use App\Models\Order\Order;
use App\Models\Product\Product;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;

/**
 * Class ExportController
 * @package App\Http\Controllers\Site
 */
class ExportController extends AbstractSiteController
{
    public function exportProducts()
    {
        $this->authorize('export', Product::class);

        return Excel::download(app(ProductsExport::class), 'products.xlsx');
    }

    public function exportOrders(Request $request)
    {
        $this->authorize('export', Order::class);

        $month = $request->query->has('month')
            ? $request->query->get('month')
            : Carbon::now()->format('Y-m');

        return Excel::download(app(OrdersExport::class)->setMonth($month), 'orders.xlsx');
    }
}