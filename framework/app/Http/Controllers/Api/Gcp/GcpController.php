<?php

namespace App\Http\Controllers\Api\Gcp;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

/**
 * Class GcpController
 * @package App\Http\Controllers\Api
 */
class GcpController extends Controller
{
    /**
     * This api will be called by GCP server after every min
     * @return void
     */
    public function processQueue()
    {
        Artisan::call('queue:work', [
            '--stop-when-empty' => true
        ]);
    }
}
