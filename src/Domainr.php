<?php

namespace Privateer\Domainr;


use GuzzleHttp\Client;

class Domainr
{
    protected $key;
    protected $client;

    /**
     * Domainr constructor.
     * @param $key
     * @param $base_uri
     */
    public function __construct($key, $base_uri)
    {
        $this->key     = $key;
        $this->client  = new Client([
            "base_uri" => $base_uri
        ]);
    }

    /**
     * @param $query
     * @param null $location
     * @param null $registrar
     * @param null $defaults
     * @return mixed
     */
    public function search($query, $location = null, $registrar = null, $defaults = null)
    {
        $result = $this->client->request("GET", "/v2/search", [
            "query" => [
                "query" => $query,
                "location" => $location,
                "registrar" => $registrar,
                "defaults" => $defaults,
                "mashape-key" => $this->key,
            ],
            "allow_redirects" => false,
        ]);

        return json_decode($result->getBody()->getContents())->results;
    }

    /**
     * @param $domain
     * @param null $registrar
     * @return mixed
     */
    public function register($domain, $registrar = null)
    {
        $result = $this->client->request('GET', "/v2/register", [
            "query" => [
                "domain" => $domain,
                "registrar" => $registrar,
                "mashape-key" => $this->key,
            ],
            "allow_redirects" => false,
        ]);

        return $result->getHeader("location")[0];
    }

    /**
     * @param $domain
     * @return mixed
     */
    public function status($domain)
    {
        $result = $this->client->request("GET", "/v2/status", [
            "query" => [
                "domain" => $domain,
                "mashape-key" => $this->key,
            ],
            "allow_redirects" => false,
        ]);

        return new Status(json_decode($result->getBody()->getContents())->status);
    }


}