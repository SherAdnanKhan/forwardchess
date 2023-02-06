<?php

namespace App\Assets;

use Illuminate\Contracts\Support\Jsonable;

/**
 * Class Location
 * @package App\Assets
 */
class Location implements Jsonable
{
    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $continentCode;

    /**
     * @var string
     */
    private $continentName;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $countryName;

    /**
     * @var string
     */
    private $regionCode;

    /**
     * @var string
     */
    private $regionName;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longitude;

    /**
     * @var array
     */
    private $location;

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string|null $ip
     *
     * @return Location
     */
    public function setIp(string $ip = null): Location
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     *
     * @return Location
     */
    public function setType(string $type = null): Location
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getContinentCode(): ?string
    {
        return $this->continentCode;
    }

    /**
     * @param string|null $continentCode
     *
     * @return Location
     */
    public function setContinentCode(string $continentCode = null): Location
    {
        $this->continentCode = $continentCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getContinentName(): ?string
    {
        return $this->continentName;
    }

    /**
     * @param string|null $continentName
     *
     * @return Location
     */
    public function setContinentName(string $continentName = null): Location
    {
        $this->continentName = $continentName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param string|null $countryCode
     *
     * @return Location
     */
    public function setCountryCode(string $countryCode = null): Location
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryName(): string
    {
        return $this->countryName;
    }

    /**
     * @param string|null $countryName
     *
     * @return Location
     */
    public function setCountryName(string $countryName = null): Location
    {
        $this->countryName = $countryName;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionCode(): ?string
    {
        return $this->regionCode;
    }

    /**
     * @param string|null $regionCode
     *
     * @return Location
     */
    public function setRegionCode(string $regionCode = null): Location
    {
        $this->regionCode = $regionCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionName(): ?string
    {
        return $this->regionName;
    }

    /**
     * @param string|null $regionName
     *
     * @return Location
     */
    public function setRegionName(string $regionName = null): Location
    {
        $this->regionName = $regionName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     *
     * @return Location
     */
    public function setCity(string $city = null): Location
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string|null $zip
     *
     * @return Location
     */
    public function setZip(string $zip = null): Location
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * @return string
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * @param string|null $latitude
     *
     * @return Location
     */
    public function setLatitude(string $latitude = null): Location
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return string
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * @param string|null $longitude
     *
     * @return Location
     */
    public function setLongitude(string $longitude = null): Location
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return array
     */
    public function getLocation(): ?array
    {
        return $this->location;
    }

    /**
     * @param array|null $location
     *
     * @return Location
     */
    public function setLocation(array $location = null): Location
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode([
            'ip'            => $this->ip,
            'type'          => $this->type,
            'continentCode' => $this->continentCode,
            'continentName' => $this->continentName,
            'countryCode'   => $this->countryCode,
            'countryName'   => $this->countryName,
            'regionCode'    => $this->regionCode,
            'regionName'    => $this->regionName,
            'city'          => $this->city,
            'zip'           => $this->zip,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'location'      => json_encode($this->location),
        ]);
    }
}