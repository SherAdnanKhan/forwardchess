<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

/**
 * Class AbstractSiteController
 * @package App\Http\Controllers\Site
 */
abstract class AbstractSiteController extends Controller
{
    /**
     * @var array
     */
    protected $viewData = [];

    /**
     * @param array $data
     *
     * @return AbstractSiteController
     */
    protected function addViewData(array $data): self
    {
        $this->viewData = array_merge($this->viewData, $data);

        return $this;
    }

    /**
     * @param array|Collection $list
     * @param int              $itemsInGroup
     *
     * @return array
     */
    protected function groupData($list, int $itemsInGroup = 5): array
    {
        $groupedList = [];
        $group       = [];
        foreach ($list as $publisher) {
            $group[] = $publisher;
            if (count($group) === $itemsInGroup) {
                $groupedList[] = $group;
                $group         = [];
            }
        }

        if (!empty($group)) {
            $groupedList[] = $group;
        }

        return $groupedList;
    }
}