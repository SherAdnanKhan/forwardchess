<?php

namespace App\Models\Order;

use App\Contracts\CountriesServiceInterface;
use App\Models\AbstractModel;

/**
 * Class Billing
 * @package App\Models\Billing
 *
 * @property int    orderId
 * @property string firstName
 * @property string lastName
 * @property string email
 * @property string phone
 * @property string company
 * @property string address1
 * @property string address2
 * @property string city
 * @property string state
 * @property string postcode
 * @property string country
 * @property string location
 *
 * @property Order  order
 */
class Billing extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'order_billing';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'orderId',
        'firstName',
        'lastName',
        'email',
        'phone',
        'company',
        'address1',
        'address2',
        'city',
        'state',
        'postcode',
        'country',
        'location',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderId', 'id');
    }

    /**
     * @return \App\Assets\Country|null|string
     */
    public function countryName(): string
    {
        if (!$this->country) {
            return '';
        }

        /** @var CountriesServiceInterface $countriesService */
        $countriesService = app(CountriesServiceInterface::class);

        return $countriesService->getCountryByCode($this->country)->name;
    }

    /**
     * @return \App\Assets\Country|null|string
     */
    public function stateName(): string
    {
        if (!$this->country || !$this->state) {
            return '';
        }

        /** @var CountriesServiceInterface $countriesService */
        $countriesService = app(CountriesServiceInterface::class);

        $state = $countriesService->getStateByCode($this->country, $this->state);

        return empty($state) ? '' : $state->name;
    }
}