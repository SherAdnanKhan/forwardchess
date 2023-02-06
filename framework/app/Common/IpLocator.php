<?php


namespace App\Common;

use App\Assets\Location;
use App\Common\Cache\CacheStorage;
use App\Contracts\IpLocatorInterface;
use GuzzleHttp\Client as HttpClient;

/**
 * Class IpLocator
 * @package App\Common
 */
class IpLocator implements IpLocatorInterface
{
    /**
     * @var CacheStorage
     */
    private CacheStorage $cacheStorage;

    /**
     * @var HttpClient
     */
    private HttpClient $client;

    /**
     * IpLocation constructor.
     *
     * @param CacheStorage $cacheStorage
     * @param HttpClient   $client
     */
    public function __construct(CacheStorage $cacheStorage, HttpClient $client)
    {
        $this->cacheStorage = $cacheStorage;
        $this->client       = $client;
    }

    /**
     * @param string $ip
     *
     * @return Location
     */
    public function get(string $ip): Location
    {
        return $this->cacheStorage->remember('location:' . $ip, 60 * 6, function () use ($ip) {
            return $this->findLocation($ip);
        });
    }

    /**
     * @param string $ip
     *
     * @return Location
     */
    private function findLocation(string $ip): Location
    {
        $uri      = env('IP_SERVICE_URL') . '/' . $ip . '?access_key=' . env('IP_SERVICE_KEY');
        $response = $this->client->get($uri);
        $data     = json_decode($response->getBody()->getContents(), true);

        return (new Location())
            ->setIp($data['ip'])
            ->setType($data['type'])
            ->setContinentCode($data['continent_code'])
            ->setContinentName($data['continent_name'])
            ->setCountryCode($data['country_code'])
            ->setCountryName($data['country_name'])
            ->setRegionCode($data['region_code'])
            ->setRegionName($data['region_name'])
            ->setCity($data['city'])
            ->setZip($data['zip'])
            ->setLatitude($data['latitude'])
            ->setLongitude($data['longitude'])
            ->setLocation($data['location']);
    }
}