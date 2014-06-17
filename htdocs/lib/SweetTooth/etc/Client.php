<?php

include_once (dirname(__FILE__) . DS . 'SdkException.php');

abstract class SweetToothClient
{    
    /**
     * CURLOPT_TIMEOUT_MS may not be recognized by older versions of PHP
     */ 
    const ST_CURLOPT_TIMEOUT_MS = 156;

    /**
     * Constants used when setting $_responseType. Default response type is array.
     */
    const RESPONSE_TYPE_OBJECT = 'object';
    const RESPONSE_TYPE_ARRAY  = 'array';
    const RESPONSE_TYPE_JSON   = 'json';

    /**
     * Default URL and API endpoint. Set desired default here.
     */
    protected $_baseDomain = 'sweettoothdev.com';
    protected $_apiEndpoint = '/index.php/rest';
    
    /**
     * Override the entire url with this field (must include subdomain) 
     * eg. http://jdoe.sweettoothapp.com/index.php/rest
     */ 
    protected $_customPlatformUrl = null;

    /**
     * Determines the data type returned by the sdk for resources
     */
    protected $_responseType = self::RESPONSE_TYPE_ARRAY;

    /**
     * Whether to use http or secure https
     */
    protected $_useHttps = false;

    /**
     * The subdomain specific to the client using the SDK.  For example, a client whose Platform Account is
     * located at account.sweettoothapp.com would have a subdomain of "account".  This is used for making
     * API calls that go to the subdomain, vs. those that go direct to the super-platform instance, which is
     * just sweettoothapp.com (such as account creation)
     */
    protected $_subdomain = null;

    /**
     * Api Credentials used to authenticate via Basic Auth
     */
    protected $_apiKey;
    protected $_apiSecret;

    /**
     * This controls the number of milliseconds that the transfer api call will timeout after
    */
    protected $_transfer_api_timeout = null;

    /**
     * Controls the number of milliseconds that CURL will timout after
     */
    protected $_curl_timeout = null;

    /**
     *  An array of API paths which should go to the subdomain'd API URL
     */
    protected $_paths_without_subdomain;

    protected $_restClientWithSubdomain = null;
    protected $_restClientSuper = null;


    /**
     * Constructor hit whenever a new instance of SweetTooth is created.
     * Creating an account doesn't require passing in any parameters.
     * When creating a channel, pass in username, password and username again. 
     * Performing an action such as a transfer on the channel requires all three feilds.
     *
     * @param string $apiKey
     * @param string $apiSecret
    */
    public function __construct($apiKey = '', $apiSecret = '', $subdomain = 'www')
    {
        $this->_apiKey = $apiKey;
        $this->_apiSecret = $apiSecret;
        $this->_subdomain = $subdomain;

        return $this;
    }
    
    /**
     * Used to read a resource. If data is passed in generates a URL-encoded query string
     * to be sent with with the request.
     *
     * @param  string                  $resource The resource we're requesting
     * @param  array                   $data     Contains data that needs to be sent with the query
     * @throws SweetToothApiException
     * @return array/json/object                 Returns the response body, defaults to array
     */
    public function get($resource, $data = array())
    {
        $path = $resource;
    
        try {
            if (isset($data) && count($data) > 0) 
            {
                $path .= '?' . http_build_query($data); 
            }

            $client = $this->getRestClient("GET", $path);
            $response = $client->get($path);
    
        } catch(Exception $e) {
            //Repackage the exception if we need to:
            throw $this->_parseException($e);
        } 
    
    
        return $response;
    }
    
    /**
     * Handles object creation.
     *
     * @param  string                   $resource  The type of object we wish to create
     * @param  array                    $data      Object creation data
     * @throws SweetToothApiException
     * @return array/json/object                   Returns the response body, defaults to array
     */
    public function post($resource, $data)
    {
        try {
            $path = $resource;
        
            $response = $this->getRestClient("POST", $path)->post($path, $data);
        
        } catch(Exception $e) {
            //Repackage the exception if we need to:
            throw $this->_parseException($e);
        }
        
        return $response;
    }

    /**
     * TODO: This needs to be implemented. User and story will use this method.
     * 
     * @param unknown_type $resource
     * @param unknown_type $data
     */
    public function put($resource, $data)
    {
        try {
            $path = $resource;

            $response = $this->getRestClient("PUT", $path)->put($path,$data);

        } catch (Exception $e){
            throw $this->_parseException($e);
        }
    }

    /**
     * TODO: This needs to be implemented. Only user supports deleting at the moment.
     * 
     * @param unknown_type $resource
     * @param unknown_type $resourceId
     */
    public function delete($resource, $resourceId)
    {
    
    }

    /**
     * Sets https or http if false.
     * 
     * @param  bool $useHttps
     * @return $this
     */
    public function setHttps($useHttps)
    {
        $this->_useHttps = $useHttps;
        return $this;
    }
    
    /**
     * Sets the response type for resources to return.
     * Use the constants in this class for setting the value
     * eg. RESPONSE_TYPE_ARRAY
     * 
     * @param string $type
     */
    public function setResponseType($type)
    {
        $this->_responseType = $type;
        return $this;
    }

    /**
     * Sets the base domain for the SDK
     * eg. sweettoothapp.com
     * 
     * @param $path
     */
    public function setBaseDomain($url)
    {
        if ($url) {
            $this->_baseDomain = $url;
        }
        return $this;
    }

    /**
     * Sets the path after the domain to the api root
     * eg. /index.php/rest
     * 
     * @param string $path
     */
    public function setApiEndpoint($path)
    {
        $this->_apiEndpoint = $path;
        return $this;
    }
    
    /**
     * Sets the entire custome api url
     * eg. http://sweettoothapp.com/index.php/rest
     * 
     * @param $url
     */
    public function setCustomPlatformUrl($url)
    {
        if ($url) {
            $this->_customPlatformUrl = $url;
        }
        return $this;
    }

    /**
     * Sets the subdomain of the api url which is specific to a
     * merchant account.
     * 
     * @param string $subdoman
     */
    public function setSubdomain($subdomain)
    {
        $this->_subdomain = $subdomain ? $subdomain : '';
        return $this;
    }

    public function setTransferApiTimeout($ms) {
        $this->_transfer_api_timeout = $ms;
    }

    public function getTransferApiTimeout() {
        return $this->_transfer_api_timeout;
    }

    /**
     * Sets the timeout for the transfer operations. 
     * 
     * @param int $ms   Number of miliseconds to time out after
     */
    public function setCurlTimeout($ms){
        $this->getRestClient("POST", '/transfer')->curl_opts[self::ST_CURLOPT_TIMEOUT_MS] = $ms;
    }
    public function getCurlTimeout(){
        return $this->getRestClient("POST", '/transfer')->curl_opts[self::ST_CURLOPT_TIMEOUT_MS];
    }


    public function getSubdomain()
    {
        $subdomain = (isset($this->_subdomain)) ? $this->_subdomain : NULL;
        return $subdomain;
    }

    public function getApiKey() {
        return $this->_apiKey;
    }

    public function getApiSecret() {
        return $this->_apiSecret;
    }

    public function prepareResponse($array)
    {
        switch ($this->_responseType) 
        {
            case self::RESPONSE_TYPE_ARRAY:
                return $array;
                break;
            case self::RESPONSE_TYPE_JSON:
                return json_encode($array);
                break;
            case self::RESPONSE_TYPE_OBJECT:
                return $this->_arrayToObject($array);
                break;
            default:
                return $array;
        }
    }
    
    /**
     * Converts an array map of data into a stdClass object.
     * @param unknown_type $array
     * @return unknown|stdClass|boolean
     */
    protected function _arrayToObject($array)
    {
        if(!is_array($array)) {
            return $array;
        }
            
        if (is_array($array) && count($array) > 0) {
            if ($this->_is_assoc_array($array)) {
                $object = new stdClass();
                foreach ($array as $name=>$value) {
                    $name = strtolower(trim($name));
                    if (!is_null($name)) {
                        $object->$name = $this->_arrayToObject($value);
                    }
                }
                return $object;
            } else {
                $newArray = array();
                foreach ($array as $index=>$value) {
                    $newArray[] = $this->_arrayToObject($value);                    
                }
                return $newArray;                
            }
        }
        else {
            return FALSE;
        }
    }

    function _is_assoc_array( $php_val ) 
    {
        if ( !is_array( $php_val ) ){
            # Neither an associative, nor non-associative array.
            return false;
        }
    
        $given_keys = array_keys( $php_val );
        $non_assoc_keys = range( 0, count( $php_val ) );
    
        if ( function_exists( 'array_diff_assoc' ) ) { # PHP > 4.3.0
            if( array_diff_assoc( $given_keys, $non_assoc_keys ) ){
                return true;
            }
            else {
                return false;
            }
        }
        else {
            if ( array_diff( $given_keys, $non_assoc_keys ) and array_diff( $non_assoc_keys, $given_keys ) ) {
                return true;
            }
            else {
                return false;
            }
        }
    }
    
    /**
     * This function builds up base url to make API calls.
     * protocol://subdomain.baseurl.com/api_endpoint
     * 
     * eg. https://billscafe.sweettoothapp.com/index.php/rest
     * 
     * @return string $baseUrl
     */
    public function getApiBaseUrl()
    {
        //Override the url if a customer endpoint is set
        if ($this->_customPlatformUrl) {
            return $this->_customPlatformUrl;
        }
      
        $url = 'http://';

        if ($this->_useHttps) {
            $url = 'https://';
        }

        //If subdomain is set include it in URL otherwise don't
        if ($this->_subdomain){
            $url .= $this->_subdomain . '.'; // eg. billscafe
        }
        $url .=  $this->_baseDomain .       // eg. sweettoothapp.com
        $this->_apiEndpoint;                // eg. /index.php/rest
        
        return $url;

    }

   /**
    * Checks to see if the method and path request require a subdomain.
    * Returns true if subdomain should be used, false otherwise.
    * 
    * @param  string $method Rest verb (GET, POST, DELETE, PUT)
    * @param  string $path   Stores the resouce we're working with (account, transfer, etc.)
    * @return boolean        Whether or not the request requires a subdomain
    */
    protected function _shouldUseSubdomain($method, $path)
    {
        return !$this->matchPath($method, $path, $this->_paths_without_subdomain);
    }


   /**
    * If the rest verb $method is a key in $paths_to_match and if its corresponding value
    * is $path return true, otherwise return false. Performs additional parsing if the
    * path contains .json.
    * 
    * @param  string $method         REST verb (GET, POST, DELETE, PUT)
    * @param  string $path           Stores the resource we're working with (account, transfer, etc.)
    * @param  array  $paths_to_match Stores an array of paths we're looking to match to
    * @return boolean                Returns true if $path is contained in $paths_to_match otherwise false
    */
    protected function matchPath($method, $path, $paths_to_match)
    {

        if (strstr($path, ".json"))
        {
            $parts = explode(".json", $path);
            $path = $parts[0];
        }

        foreach ($paths_to_match as $path_to_match => $methods)
        {
            if (strstr($path, $path_to_match))
            {
                if (in_array($method, $methods))
                {
                    return true;
                }
            }
        }
        return false;

    }


   /**
    * Setup prior to making REST calls. Determines if authentication or a subdomain is required
    * when making the request.
    * 
    * @param  string $method    REST verb (GET, POST, DELETE, PUT)
    * @param  string $path      Stores the resource we're working with (account, transfer, etc.)
    * @return array/json/object Response body
    */
    public function getRestClient($method, $path = '')
    {
        /*    
        *    The reason we're matching /account/ (with trailing slash) is because we want /account/<license_key>
        *    to not be subdomain'd, but we want /account to be subdomain'd - this is used in mmst system/config
        *    to grab account details for a subdomain'd account. 
        */
        $this->_paths_without_subdomain = array(
            '/account' => array('POST'),
            '/account/' => array('GET'),   
            '/prediction' => array('POST'), 
                                            
        );

        //Calls that require no authentication when being made, add rules here
        $paths_without_auth = array(        
            '/channel/key' => array('GET'),
            '/account' => array('POST'),
            '/account/' => array('GET'),
        );

        if (!$this->matchPath($method, $path, $paths_without_auth))
        {
            if (!$this->_apiKey) {
                throw new SweetToothSdkException("Sweet Tooth API Key not specified.",
                    SweetToothSdkException::CREDENTIALS_NOT_SPECIFIED);
            }
            if (!$this->_apiSecret) {
                throw new SweetToothSdkException("Sweet Tooth API Secret not specified.",
                    SweetToothSdkException::CREDENTIALS_NOT_SPECIFIED);
            }
        }

        if ($this->_shouldUseSubdomain($method, $path)) {
            return $this->getRestClientWithSubdomain();
        }

        return $this->getRestClientSuper();
    }


    /**
     * Called when subdomain is required in making the rest call
     * 
     * @return Pest rest client used to speak to the Sweet Tooth Platform
     */

    public function getRestClientWithSubdomain()
    {
        if ($this->_restClientWithSubdomain) {
            return $this->_restClientWithSubdomain;
        }
        
        $baseUrl = $this->getApiBaseUrl();
        $pest = new PestJSON($baseUrl);


        /* Defaulting timeout to 10s.  The Pest library does not default the timeout.
         * CURLOPT_TIMEOUT_MS may not be recognized by older versions of PHP
         */
        $pest->curl_opts[/*CURLOPT_TIMEOUT_MS*/ 156] = 10000;

        $pest->setupAuth($this->_apiKey, $this->_apiSecret);
        $this->_restClientWithSubdomain = $pest;

        return $this->_restClientWithSubdomain;
    }


    /**
     * Called when subdomain is not required in making the rest call
     * 
     * @return Pest rest client used to speak to the Sweet Tooth Platform
     *         
     */

    public function getRestClientSuper()
    {
        if ($this->_restClientSuper) {
            return $this->_restClientSuper;
        }
    
        $baseUrl = $this->getApiBaseUrl();
        $pest = new PestJSON($baseUrl);

        $pest->setupAuth($this->_apiKey, $this->_apiSecret);
        $this->_restClientSuper = $pest;
    
        return $this->_restClientSuper;
    }

    /**
     * Parses an exception message to see if there is JSON content and it can be repackaged as a SweetToothApiException
     * 
     * @param Exception $e
     */
    protected function _parseException($e) {
        if(! $e->getMessage()) {
            return $e;
        }
    
    
        $response =  (array) json_decode($e->getMessage());
    
        if(!$response || !isset($response['error'])) {
            return $e;
        }
    
        $result = (array) $response['error'];
    
    
        if( !$result || empty($result)) {
            return $e;
        }
    
        $e = new SweetToothApiException($result);
    
        return $e;
    }
    
}