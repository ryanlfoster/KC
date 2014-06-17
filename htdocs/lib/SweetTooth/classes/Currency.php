<?php

class SweetToothCurrency
{
    private $prefix = "/currency";
    private $client;

    public function __construct($client) {
        $this->client = $client;
    }

    public function get() {
        $result = $this->client->get($this->prefix);
        return $this->client->prepareResponse($result);
    }

    /**
     * Cleans up memory used when working with currency objects.
     */
    public function __destruct(){
        unset($this->prefix);
        unset($this->array);
    }
}
