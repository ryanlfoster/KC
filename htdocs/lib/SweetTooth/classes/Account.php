<?php

/**
 *
 */
class SweetToothAccount
{
    
    const RESOURCE_ACCOUNT   = '/account';
    
    private $prefix = "/account";
    private $client;

    public function __construct($client) {
        $this->client = $client;
    }

    /**
     * Creates a SweetTooth account. Checks to see if the instance of SweetTooth already has a key/secret
     * meaning an account has already been created using the SweetTooth instance
     * 
     * @param  array              $fields Contains account creation data
     * @return array/json/object          Response body, defaults to array
     */
    public function create($fields)
    {
        if ($this->client->getApiKey() || $this->client->getApiSecret()) {
            throw new Exception("You are attempting to create an account but you already have API Credentials configured (rewards/platform/apikey and rewards/platform/apisecret)");
        }

        $result = $this->client->post($this->prefix, $fields);
        return $this->client->prepareResponse($result);
    }

    public function get($id = null) 
    {
        if ($id) {
            $result = $this->client->get($this->prefix . '/' . $id);
        } else {
            $result = $this->client->get($this->prefix);
        }
        return $this->client->prepareResponse($result);
    }

    /**
     * Cleans up variables used when working with account objects.
     */
    public function __destruct(){
        unset($this->prefix);
        unset($this->client);
    }
}