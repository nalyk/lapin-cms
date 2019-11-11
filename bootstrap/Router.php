<?php

namespace Bootstrap;

use App\Controller\IndexController;
use App\Controller\AdminController;
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
        $slimapp = $this->app;

        $this->app->get('/', IndexController::class . ':index')->setName('home_index');

        $this->app->group('/admin', function() {
            $this->get('/dashboard', AdminController::class . ':dashboard')->setName('admin_dashboard');
            $this->any('/types[/{name}]', AdminController::class . ':typesEdit')->setName('admin_types_edit');
            // deployd objects
            $this->group('/deployd', function() {
                $this->get('/new', DeploydController::class . ':new')->setName('deployd_new');
                $this->get('/list', DeploydController::class . ':list')->setName('deployd_list');
                $this->post('/create', DeploydController::class . ':create')->setName('deployd_create');
                $this->post('/update', DeploydController::class . ':update')->setName('deployd_update');
                $this->get('/edit/{id}', DeploydController::class . ':edit')->setName('deployd_edit');
                $this->post('/delete/{id}', DeploydController::class . ':delete')->setName('deployd_delete');
            });
        });
    }
}
