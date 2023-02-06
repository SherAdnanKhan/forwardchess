<?php

use App\Contracts\BlockedProductsInterface;
use App\Http\Controllers\Api\Cart\CartController;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

if (!function_exists('addResource')) {
    function addResource($url, $controller, array $options = []): PendingResourceRegistration
    {
        $options = array_merge([
            'name'       => $url,
            'table'      => false,
            'restore'    => false,
            'destroy'    => true,
            'routeParam' => 'id'
        ], $options);

        $name = $options['name'];

        if ($options['table']) {
            Route::get("{$url}/tables", [$controller, 'tables'])->name("{$name}.tables");
        }

        if ($options['restore']) {
            Route::put("{$url}/{{$options['routeParam']}}/restore", [$controller, 'restore'])->name("{$name}.restore");
        }

        return Route::apiResource($url,
            $controller,
            [
                'names' => [
                    'index'   => "{$name}.index",
                    'store'   => "{$name}.store",
                    'show'    => "{$name}.show",
                    'update'  => "{$name}.update",
                    'destroy' => "{$name}.destroy",
                ]
            ]);
    }
}

if (!function_exists('productsPageUrl')) {
    function productsPageUrl($categoryUrl = null, $publisherName = null, $searchText = null, array $addParams = []): string
    {
        $url = empty($categoryUrl)
            ? route('site.products.index')
            : route('site.products.index', ['category' => $categoryUrl]);

        $queryParams = [];
        if (!empty($publisherName)) {
            $queryParams['publishers'][] = kebab_case($publisherName);
        }

        if (!empty($publisherName)) {
            $queryParams['search'] = $searchText;
        }

        $queryParams = array_merge($queryParams, $addParams);
        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        return $url;
    }
}

if (!function_exists('addCartRoutes')) {
    function addCartRoutes()
    {
        Route::get('cart', [CartController::class, 'index']);
        Route::post('cart/coupon/{code}', [CartController::class, 'addCoupon']);
        Route::post('cart/gift/{code}', [CartController::class, 'addGift']);

        Route::post('cart/items', [CartController::class, 'addItem']);
        Route::delete('cart/items/{code}', [CartController::class, 'removeItem']);
        Route::put('cart/items/{code}', [CartController::class, 'updateItem']);
    }
}

/**
 * @param float $number
 *
 * @return int
 */
if (!function_exists('toIntAmount')) {
    function toIntAmount(float $number): int
    {
        return (int)number_format($number * 100, 0, '', '');
    }
}

/**
 * @param int $number
 *
 * @return float
 */
if (!function_exists('toFloatAmount')) {
    function toFloatAmount(int $number): float
    {
        return $number / 100;
    }
}

if (!function_exists('redirect_now')) {
    function redirect_now($url, $code = 302)
    {
        try {
            \App::abort($code, '', ['Location' => $url]);
        } catch (\Exception $exception) {
            // the blade compiler catches exceptions and rethrows them
            // as ErrorExceptions :(
            //
            // also the __toString() magic method cannot throw exceptions
            // in that case also we need to manually call the exception
            // handler
            $previousErrorHandler = set_exception_handler(function () {
            });
            restore_error_handler();
            call_user_func($previousErrorHandler, $exception);
        }

        die;
    }
}

if (!function_exists('transformArray')) {
    function transformArray(array $list): array
    {
        uksort($list, function ($a, $b) {
            return $a <=> $b;
        });

        foreach ($list as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            $list[$key] = transformArray($value);
        }

        return $list;
    }
}

if (!function_exists('getMonthName')) {
    function getMonthName($monthNumber)
    {
        return date("F", mktime(0, 0, 0, $monthNumber, 1));
    }
}

if (!function_exists('realAsset')) {
    function realAsset($path)
    {
        return Request::root() . '/' . ltrim($path, '/');
    }
}

if (!function_exists('isAllowedToBuy')) {
    function product_bought(int $productId): bool
    {
        return app(BlockedProductsInterface::class)->hasProduct($productId) ? true : false;
    }
}

if (!function_exists('isProduction')) {
    function isProduction(): bool
    {
        return app()->environment('production');
    }
}
