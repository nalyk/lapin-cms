<?php

namespace App\Model\Core;

class Deployd
{
    protected $apiserver;

    public function __construct($container, $apiserver)
    {
    	$this->container = $container;
        $this->apiserver = $apiserver;
        $this->guzzle = $this->container['guzzle'];
    }

    public function post(string $collection, string $id = null, array $data)
    {

    }

    public function put(string $collection, array $data)
    {

    }

    /**
     * Get values from Deployd API
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
    			$res = $this->guzzle->request('GET', $this->apiserver."/".$collection);
	            $response = json_decode($res->getBody(), true);
			} catch (ClientException $e) {
			    echo Psr7\str($e->getRequest());
			    echo Psr7\str($e->getResponse());
			}
    	} elseif ($query == null) {
            try {
                $res = $this->guzzle->request('GET', $this->apiserver."/".$collection."/".$id);
                $response = json_decode($res->getBody(), true);
            } catch (ClientException $e) {
                echo Psr7\str($e->getRequest());
                echo Psr7\str($e->getResponse());
            }
        } else {
            try {
                $res = $this->guzzle->request('GET', $this->apiserver."/".$collection."/?".$query);
                $response = json_decode($res->getBody(), true);
            } catch (ClientException $e) {
                echo Psr7\str($e->getRequest());
                echo Psr7\str($e->getResponse());
            }
        }

    	return $response;
    }

    public function del(string $collection, array $data)
    {

    }

}