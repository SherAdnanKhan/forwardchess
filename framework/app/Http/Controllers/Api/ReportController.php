<?php

namespace App\Http\Controllers\Api;

use App\Assets\DateRange;
use App\Exceptions\AuthorizationException;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Repositories\ReportRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class ReportController
 * @package App\Http\Controllers\Api
 */
class ReportController extends Controller
{
    /**
     * @param Request          $request
     * @param ReportRepository $repository
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function countrySales(Request $request, ReportRepository $repository)
    {
        $this->checkUser($request->user());

        $range = $this->getYearRange($request->input('year'));

        return response()->json($repository->getCountrySales($range));
    }

    /**
     * @param Request          $request
     * @param ReportRepository $repository
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function bestSellers(Request $request, ReportRepository $repository)
    {
        $this->checkUser($request->user());

        $range = $this->getMonthRange($request->input('date'));

        return response()->json($repository->getBestSellers($range));
    }

    /**
     * @param Request          $request
     * @param ReportRepository $repository
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function customersAnalytics(Request $request, ReportRepository $repository)
    {
        $this->checkUser($request->user());

        $range = $this->getMonthRange($request->input('date'));

        return response()->json($repository->getCustomersAnalytics($range));
    }

    /**
     * @param User $user
     *
     * @throws AuthorizationException
     */
    private function checkUser(User $user)
    {
        if (!$user->isAdmin) {
            throw new AuthorizationException();
        }
    }

    /**
     * @param string|null $year
     *
     * @return DateRange
     */
    private function getYearRange(string $year = null): DateRange
    {
        $nowDate = Carbon::now();
        $date    = empty($year) ? $nowDate : new Carbon($year . '-01-01 00:00:00');

        $startDate = clone $date->startOfYear();
        $endDate   = clone $date->endOfYear();

        return DateRange::make($startDate, Carbon::now()->min($endDate));
    }

    /**
     * @param string|null $date
     *
     * @return DateRange
     */
    private function getMonthRange(string $date = null): DateRange
    {
        $nowDate = Carbon::now();
        $date    = empty($date) ? $nowDate : new Carbon($date);

        $startDate = clone $date->startOfMonth();
        $endDate   = clone $date->endOfMonth();

        return DateRange::make($startDate, Carbon::now()->min($endDate));
    }
}
