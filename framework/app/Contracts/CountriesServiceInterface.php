<?php

namespace App\Contracts;

use App\Assets\Country;
use App\Assets\State;
use Illuminate\Support\Collection;

/**
 * Interface CountriesServiceInterface
 * @package App\Contracts
 */
interface CountriesServiceInterface
{
    /**
     * @return Collection
     */
    public function getCountries(): Collection;

    /**
     * @param string $code
     *
     * @return Country|null
     */
    public function getCountryByCode(string $code): ?Country;

    /**
     * @param string $countryCode
     * @param string $code
     *
     * @return mixed
     */
    public function getStateByCode(string $countryCode, string $code): ?State;

    /**
     * @param string $countryCode
     *
     * @return Collection
     */
    public function getStates(string $countryCode): Collection;
}