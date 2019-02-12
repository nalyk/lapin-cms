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
            // deployd objects
            $this->get('/deployd/new', DeploydController::class . ':new')->setName('deployd_new');
            $this->post('/deployd/create', DeploydController::class . ':create')->setName('deployd_create');
            $this->post('/deployd/update', DeploydController::class . ':update')->setName('deployd_update');
            $this->get('/deployd/edit/{id}', DeploydController::class . ':edit')->setName('deployd_edit');
            $this->post('/deployd/delete/{id}', DeploydController::class . ':delete')->setName('deployd_delete');
        });

        /*
        $app->group('/v1', function () {
            $this->group('/auth', function () {
                $this->map(['GET', 'POST'], '/login', 'App\controllers\AuthController:login');
                $this->map(['GET', 'POST'], '/logout', 'App\controllers\AuthController:logout');
                $this->map(['GET', 'POST'], '/signup', 'App\controllers\AuthController:signup');
            });

            $this->group('/events', function () {
                $this->get('', 'App\controllers\EventController:getEvents');
                $this->post('', 'App\controllers\EventController:createEvent');

                $this->group('/{eventId}', function () {
                    $this->get('', 'App\controllers\EventController:getEvent');
                    $this->put('', 'App\controllers\EventController:updateEvent');
                    $this->delete('', 'App\controllers\EventController:deleteEvent');            
                });
            });
        });
        */
    }
}
