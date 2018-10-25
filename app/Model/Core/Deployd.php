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

    public function post(string $collection, array $data)
    {

    }

    public function put(string $collection, array $data)
    {

    }

    public function get(string $collection, string $id = null, array $query = null)
    {
    	if ($id == null && $query == null) {
    		try {
    			$res = $this->guzzle->request('GET', $this->apiserver."/".$collection);
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