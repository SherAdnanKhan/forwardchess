<?php

namespace App\Repositories;

use App\Assets\SortBy;
use App\Builders\GiftBuilder;
use App\Exceptions\CommonException;
use App\Models\AbstractModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class GiftRepository
 * @package App\Repositories
 */
class GiftRepository extends AbstractModelRepository
{
    /**
     * @param array  $search
     * @param SortBy $sortBy
     *
     * @return Builder
     */
    public function getBuilder(array $search = [], SortBy $sortBy = null): Builder
    {
        $search['sortBy'] = $sortBy;

        return GiftBuilder::make($search)->init();
    }

    /**
     * @param array|null $data
     *
     * @return AbstractModel
     * @throws CommonException
     */
    public function store(array $data = null): AbstractModel
    {
        if (empty($data)) {
            $this->invalidFormDataException();
        }

        if (empty($data['code'])) {
            $data['code'] = strtoupper(uniqid('gift-'));
        }

        if (!empty($data['amount'])) {
            $data['originalAmount'] = $data['amount'];
        }

        if (empty($data['expireDate'])) {
            $data['expireDate'] = Carbon::now()->addYear(2)->endOfDay();
        }

        $model = $this->getResource()->create($data);
        if (!$model) {
            $this->dataNotSavedException();
        }

        $model = $this->getById($model->id);

        if (method_exists($this, self::CB_AFTER_STORE)) {
            call_user_func_array([$this, self::CB_AFTER_STORE], [$model]);
        }

        return $model;
    }
}