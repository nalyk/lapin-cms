<?php

namespace Bootstrap;

use App\Controller\IndexController;
use App\Controller\DeploydController;

class Router
{
    /**
     * Slim application
     * @var \Slim\App
     */
    private $app;

    /**
     * Save \Slim\App instance
     * @param \Slim\App $app slim application
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Register routes
     * @return void
     */
    public function registerRoutes()
    {
        $this->app->get('/', IndexController::class . ':index')->setName('index');

        // deployd objects
        $this->app->get('/deployd/new', DeploydController::class . ':index')->setName('deployd_new');
        $this->app->post('/deployd/create', DeploydController::class . ':index')->setName('deployd_create');
        $this->app->post('/deployd/update', DeploydController::class . ':index')->setName('deployd_update');
        $this->app->get('/deployd/edit/{id}', DeploydController::class . ':index')->setName('deployd_edit');
        $this->app->post('/deployd/delete/{id}', DeploydController::class . ':index')->setName('deployd_delete');
    }
}
