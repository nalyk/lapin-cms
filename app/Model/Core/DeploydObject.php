<?php

namespace App\Model\Core;

class DeploydObject
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
     * Create Object for Deployd API
     * @param  string   $collection     collection name
     * @param  array    $data           object data
     * @return array
     */
    public function create()
    {

    }

    /**
     * Update Object for Deployd API
     * @param  string   $collection     collection name
     * @param  array    $data           object data
     * @return array
     */
    public function update()
    {
        
    }

    /**
     * Delete Object for Deployd API
     * @param  string   $collection     collection name
     * @param  array    $data           object data
     * @return array
     */
    public function delete()
    {
        
    }

    /**
     * Sync Object for Deployd API
     * @param  string   $collection     collection name
     * @param  array    $data           object data
     * @return array
     */
    public function sync()
    {
        
    }

}