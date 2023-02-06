<?php

namespace App\Http\Controllers\Api;

use App\Assets\WishlistReport;
use App\Contracts\WishlistServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class WishlistsController
 * @package App\Http\Controllers\Api
 */
class WishlistsController extends Controller
{
    /**
     * @param Request                  $request
     * @param WishlistServiceInterface $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tables(Request $request, WishlistServiceInterface $service)
    {
        $wishlistReport = WishlistReport::make([
            'query'       => $request->input('query'),
            'sortBy'      => $request->input('orderBy'),
            'sortDir'     => ($request->input('ascending') == 1) ? 'ASC' : 'DESC',
            'page'        => $request->input('page', 1),
            'rowsPerPage' => $request->input('limit', 20)
        ]);

        $response = $service->report($wishlistReport);

        return response()->json($response);
    }
}
