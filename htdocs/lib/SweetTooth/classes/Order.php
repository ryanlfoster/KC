<?php


/**
 *
 */
class SweetToothOrder
{
    private $prefix = "/order";
    private $client;

    public function __construct($client) {
        $this->client = $client;
    }

    public function get($id = null) {
        if (!is_null($id)) {
            $result = $this->client->get($this->prefix . '/' . $id);
            return $this->client->prepareResponse($result['order']);
        } else {
            return $this->search();
        }
    }

    public function search($filters = null) {
        $result = $this->client->get($this->prefix . '/' . 'search', $filters);
        return $this->client->prepareResponse($result['order']);
    }

    /**
     * Creates orders under the specified channel in $fields.
     * 
     * @param  array $fields      Order creation data
     * @return array/json/object  Response body
     */
    public function create($fields){
        return $this->client->post($this->prefix, $fields);
    }

    /**
     * Cleans up memory used when working with order objects.
     */
    public function __destruct(){
        unset($this->prefix);
        unset($this->array);
    }
}