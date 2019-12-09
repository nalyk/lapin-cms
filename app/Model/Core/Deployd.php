<?php

namespace App\Model\Core;

class Deployd
{
    /**
     * Dependency container provided by Slim
     * @var \Slim\Container
     */
    protected $container;

    /**
     * DPD settings
     * @var array
     */
    protected $settings;

    public function __construct($container)
    {
    	$this->container = $container;
        $this->settings = $this->container->get('settings')['dpd'];
        $this->apiserver = $this->settings['protocol']."://".$this->settings['host'];
        $this->guzzle = $this->container['guzzle'];
    }

    /**
     * Post object to Deployd API
     * @param  string   $collection     collection name
     * @param  array    $data           object data
     * @return array
     */
    public function post(string $collection, array $data)
    {
        try {
            $res = $this->guzzle->request("POST", $this->apiserver."/".$collection, [
                "form_params" => $data
            ]);
            $response["_status"] = true;
            $response["data"] = json_decode($res->getBody(), true);
        } catch (ClientException $e) {
            // echo Psr7\str($e->getRequest());
            // echo Psr7\str($e->getResponse());
            $response["_status"] = false;
            $response["_error"] = Psr7\str($e->getResponse());
        }
        
        return $response;
    }

    /**
     * Update object in Deployd API
     * @param  string   $collection     collection name
     * @param  string   $id             document id
     * @param  array    $data           object data
     * @return array
     */
    public function put(string $collection, string $id, array $data)
    {
        try {
            $res = $this->guzzle->request("PUT", $this->apiserver."/".$collection."/".$id, [
                "form_params" => $data
            ]);
            $response["_status"] = true;
            $response["data"] = json_decode($res->getBody(), true);
        } catch (ClientException $e) {
            // echo Psr7\str($e->getRequest());
            // echo Psr7\str($e->getResponse());
            $response["_status"] = false;
            $response["_error"] = Psr7\str($e->getResponse());
        }
        
        return $response;
    }

    /**
     * Get object(s) from Deployd API
     * @param  string   $collection     collection name
     * @param  string   $id             document id
     * @param  string   $query          get/search query
     * @return array
     */
    public function get(string $collection, string $id = null, string $query = null)
    {
        //$this->container->monolog->warning(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);
    	if ($id == null && $query == null) {
    		try {
    			$res = $this->guzzle->request("GET", $this->apiserver."/".$collection);
	            $response["_status"] = true;
                $response["data"] = json_decode($res->getBody(), true);
			} catch (ClientException $e) {
			    // echo Psr7\str($e->getRequest());
                // echo Psr7\str($e->getResponse());
                $response["_status"] = false;
                $response["_error"] = Psr7\str($e->getResponse());
			}
    	} elseif ($query == null) {
            $res = $this->guzzle->request("GET", $this->apiserver."/".$collection."/".$id);
            $statuscode = $res->getStatusCode();
            if (200 === $statuscode) {
                $response["_status"] = true;
                $response["data"] = json_decode($res->getBody(), true);
            } elseif (404 === $statuscode) {
                $response["_status"] = false;
                $response["_error"] = "Object does not exist";
            } else {
                throw new ClientException(Psr7\str($e->getResponse()));
            }
            /*
            try {
                $res = $this->guzzle->request("GET", $this->apiserver."/".$collection."/".$id);
                $response["_status"] = true;
                $response["data"] = json_decode($res->getBody(), true);
            } catch (ClientException $e) {
                // echo Psr7\str($e->getRequest());
                // echo Psr7\str($e->getResponse());
                $response["_status"] = false;
                $response["_error"] = Psr7\str($e->getResponse());
            }
            */
        } else {
            try {
                $res = $this->guzzle->request("GET", $this->apiserver."/".$collection."/?".$query);
                $response["_status"] = true;
                $response["data"] = json_decode($res->getBody(), true);
            } catch (ClientException $e) {
                // echo Psr7\str($e->getRequest());
                // echo Psr7\str($e->getResponse());
                $response["_status"] = false;
                $response["_error"] = Psr7\str($e->getResponse());
            }
        }

    	return $response;
    }

    /**
     * Delete object from Deployd API
     * @param  string   $collection     collection name
     * @param  string   $id             document id
     * @return array
     */
    public function del(string $collection, string $id)
    {
        try {
            $res = $this->guzzle->request("DELETE", $this->apiserver."/".$collection."/".$id);
            $response["_status"] = true;
            $response["data"] = json_decode($res->getBody(), true);
        } catch (ClientException $e) {
            // echo Psr7\str($e->getRequest());
            // echo Psr7\str($e->getResponse());
            $response["_status"] = false;
            $response["_error"] = Psr7\str($e->getResponse());
        }

        return $response;
    }

}
