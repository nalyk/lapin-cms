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

        $this->app->get('/admin/dashboard', \App\Controller\AdminController::class . ':dashboard')->setName('admin_index');

        // deployd objects
        $this->app->get('/admin/deployd/new', \App\Controller\DeploydController::class . ':new')->setName('deployd_new');
        $this->app->post('/admin/deployd/create', \App\Controller\DeploydController::class . ':create')->setName('deployd_create');
        $this->app->post('/admin/deployd/update', \App\Controller\DeploydController::class . ':update')->setName('deployd_update');
        $this->app->get('/admin/deployd/edit/{id}', \App\Controller\DeploydController::class . ':edit')->setName('deployd_edit');
        $this->app->post('/admin/deployd/delete/{id}', \App\Controller\DeploydController::class . ':delete')->setName('deployd_delete');
    }
}
