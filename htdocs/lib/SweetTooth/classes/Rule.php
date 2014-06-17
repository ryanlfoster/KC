<?php



class SweetToothRule
{
    private $prefix = "/rule";
    private $client;

    public function __construct($client) {
        $this->client = $client;
    }

    public function put($filters) {
        $result = $this->client->put($this->prefix, $filtes);
        return $this->client->prepareResponse($result);
    }

    /**
     * Cleans up memory used when working with Rule objects.
     */
    public function __destruct(){
        unset($this->prefix);
        unset($this->client);
    }
}
