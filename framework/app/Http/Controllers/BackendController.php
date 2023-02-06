<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

/**
 * Class HomeController
 * @package App\Http\Controllers\Backend
 */
class BackendController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function page(Request $request)
    {
        JavaScriptFacade::put([
            'user'       => UserResource::make($request->user())->jsonSerialize(),
            'baseURL'    => $request->root(),
            'apiBaseURL' => $request->root() . '/api',
        ]);

        return view('backend.main');
    }
}
