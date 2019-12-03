<?php

namespace Bootstrap;

use App\Controller\IndexController;
use App\Controller\AdminController;
use App\Controller\UserController;
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

        $this->app->get('/[{lang}/]', IndexController::class . ':index')->setName('home_index');

        $this->app->get('/{lang}/login', UserController::class . ':login')->setName('user_login');
        $this->app->post('/{lang}/auth', UserController::class . ':auth')->setName('user_auth');

        $this->app->group('/{lang}/admin', function() {
            $this->get('/dashboard', AdminController::class . ':dashboard')->setName('admin_dashboard');
            $this->get('/types[/{name}]', AdminController::class . ':types')->setName('admin_types');
            $this->get('/categories[/{name}]', AdminController::class . ':categories')->setName('admin_categories');
            $this->get('/articles', AdminController::class . ':articles')->setName('admin_articles');
            $this->get('/articles/new', AdminController::class . ':articlesCreate')->setName('admin_articles_create');
            $this->group('/ajax', function() {
                $this->group('/types', function() {
                    $this->any('/{collection}', AdminController::class . ':ajaxTypes')->setName('admin_ajax_types');
                });
                $this->any('/categories[/{name}]', AdminController::class . ':ajaxCategories')->setName('admin_ajax_categories');
                $this->any('/articles[/{id}]', AdminController::class . ':ajaxArticles')->setName('admin_ajax_articles');
                $this->group('/modal', function() {
                    $this->any('/{name}', AdminController::class . ':ajaxModal')->setName('admin_ajax_modal');
                });
            });
        });
    }
}
