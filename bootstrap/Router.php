<?php

namespace Bootstrap;

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
        $this->app->get('/', \App\Controller\IndexController::class . ':index')->setName('index');

        $this->app->group('/admin', function (\Slim\App $app) {

            $app->get('[/]', \App\Controller\AdminController::class . ':index')->setName('admin_new');

            // deployd objects
            $app->get('/deployd/new', \App\Controller\DeploydController::class . ':new')->setName('deployd_new');
            $app->post('/deployd/create', \App\Controller\DeploydController::class . ':create')->setName('deployd_create');
            $app->post('/deployd/update', \App\Controller\DeploydController::class . ':update')->setName('deployd_update');
            $app->get('/deployd/edit/{id}', \App\Controller\DeploydController::class . ':edit')->setName('deployd_edit');
            $app->post('/deployd/delete/{id}', \App\Controller\DeploydController::class . ':delete')->setName('deployd_delete');
        });
    }
}
