<?php

namespace App\Services;

use App\Assets\Country;
use App\Assets\State;
use App\Contracts\CacheStorageInterface;
use App\Contracts\CountriesServiceInterface;
use Carbon\Carbon;
use DougSisk\CountryState\CountryState;
use Illuminate\Support\Collection;

/**
 * Class CountriesService
 * @package App\Services
 */
class CountriesService implements CountriesServiceInterface
{
    const COUNTRIES_KEY = 'countries';
    const STATES_KEY    = 'states';

    /**
     * @var CacheStorageInterface
     */
    private $cache;

    /**
     * @var CountryState
     */
    private $repository;

    /**
     * CountriesService constructor.
     *
     * @param CacheStorageInterface $cache
     * @param CountryState          $repository
     */
    public function __construct(CacheStorageInterface $cache, CountryState $repository)
    {
        $this->cache      = $cache;
        $this->repository = $repository;
    }

    /**
     * @return Collection
     */
    public function getCountries(): Collection
    {
        return $this->cache->remember(self::COUNTRIES_KEY, $this->getStorageTime(), function () {
            $countries = collect([]);
            foreach ($this->repository->getCountries() as $code => $name) {
                $countries->put($this->formatCode($code), new Country($code, $name));
            }

            return $countries;
        });
    }

    /**
     * @param string $code
     *
     * @return Country|null
     */
    public function getCountryByCode(string $code): ?Country
    {
        return $this->getCountries()->get($this->formatCode($code));
    }

    /**
     * @param string $countryCode
     * @param string $code
     *
     * @return State|null
     */
    public function getStateByCode(string $countryCode, string $code): ?State
    {
        return $this->getStates($countryCode)->first(function (State $state) use ($code) {
            return ($state->code === $code);
        });
    }

    /**
     * @param string $countryCode
     *
     * @return Collection
     */
    public function getStates(string $countryCode): Collection
    {
        $countryCode = $this->formatCode($countryCode);
        $states      = $this->cache->get(self::STATES_KEY);
        if (empty($states) || empty($states[$countryCode])) {
            $states[$countryCode] = $this->fetchCountryStates($countryCode);
            $this->cache->put(self::STATES_KEY, $states, $this->getStorageTime());
        }

        return $states[$countryCode];
    }

    /**
     * @param string $countryCode
     *
     * @return Collection
     */
    private function fetchCountryStates(string $countryCode): Collection
    {
        $states = collect([]);
        foreach ($this->repository->getStates($countryCode) as $code => $name) {
            if (empty($code) || empty($name)) {
                continue;
            }

            $states->push(new State($code, $name));
        }

        return $states;
    }

    /**
     * @return Carbon
     */
    private function getStorageTime(): Carbon
    {
        return Carbon::now()->addMonth(3);
    }

    /**
     * @param string $code
     *
     * @return string
     */
    private function formatCode(string $code): string
    {
        return strtoupper($code);
    }
}