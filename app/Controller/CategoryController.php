<?php

namespace App\Controller;

class CategoryController
{
    /**
     * Dependency container provided by Slim
     * @var \Slim\Container
     */
    protected $container;

    /**
     * Save dependency container
     * @param \Slim\App $app slim application
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->logger = $this->container->get('monolog');
    }

    public function getAllCategories()
    {
        $items = $this->container->deployd->get('categories', null, null);
    }

}