<?php


/**
 *
 */
class SweetToothChannel
{
    
    private $prefix = "/channel";
    private $client;

    public function __construct($client) {
        $this->client = $client;
    }

    /**
     * Creates a channel using an instance of Sweet Tooth that has been decleared with 
     * account user name, password and subdomain.
     * 
     * @param  array              $fields  Channel creation data
     * @return array/json/object           Response body, defaults to array
     */
    public function create($fields)
    {
        $result = $this->client->post($this->prefix, $fields);
        return $this->client->prepareResponse($result);
    }

    /**
     * Returns channel data.
     * 
     * @return array/json/object Channel data, defaults to array
     */
    public function get() {
        $result = $this->client->get($this->prefix);

        $account = $result;

        return $this->client->prepareResponse($account);
    }

    public function st_key($key) {
        $prefix = $this->prefix . "/key";
        $result = $this->client->get($prefix . '/' . $key);

        return $this->client->prepareResponse($result['channel']);
    }

    /**
     * Cleans up memory used when working with channel objects.
     */
    public function __destruct(){
        unset($this->prefix);
        unset($this->client);
    }
}