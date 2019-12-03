<?php

namespace Bootstrap;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Server\MiddlewareInterface as Middleware;
use \Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Middlewares
{
    /**
     * Slim application
     * @var \Slim\App
     */
    private $app;

    /**
     * Dependency container provided by Slim
     * @var \Slim\Container
     */
    private $container;

    /**
     * Save \Slim\App instance and dependency container
     * @param \Slim\App $app slim application
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->container = $app->getContainer();
    }

    /**
     * Register all middlewares
     * @return void
     */
    public function registerMiddlewares()
    {
        //$this->registerCsrf();
        $this->registerSession();
        $this->registerLanguage();
        $this->registerIpAddress();
        $this->registerAgent();
        $this->registerTrailingSlash();
        $this->registerErrorMiddleware();
        $this->registerAuth();
    }

    /**
     * Register CSRF middleware (provided by Slim-CSRF)
     * Provides CSRF protection
     * @return void
     */
    public function registerCsrf()
    {
        $this->app->add($this->container->get('csrf'));
    }

    /**
     * Register Session middleware (provided by Slim-Session)
     * Provides an easy way to manage sessions
     * @return void
     */
    public function registerSession()
    {
        $this->app->add(new \Slim\Middleware\Session([
            'name' => 'LapinCMS',
            'autorefresh' => true,
            'lifetime' => '1 day'
        ]));
    }

    public function registerLanguage()
    {
        $available_languages = $this->container['settings']['cms']['languages']["avaliable"];
        $default_language = $this->container['settings']['cms']['languages']["default"];
        $this->app->add( new \App\Middleware\LanguageMiddleware($available_languages, $default_language, $this->container) );
    }

    /**
     * Register IP Address middleware
     * @return void
     */
    public function registerIpAddress()
    {
        $this->app->add(new \RKA\Middleware\IpAddress(true, []));
    }

    /**
     * Register User Agent (https://github.com/zguillez/slim-mobile-detect) middleware
     * @return void
     */
    public function registerAgent()
    {
        $this->app->add(function ($request, $response, $next) {
            $request  = new \Slim\Http\MobileRequest($request);
            $response = new \Slim\Http\MobileResponse($response);
        
            return $next($request, $response);
        });

        // custom PSR-15 Middleware example
        /*
        $this->app->add(new class implements Middleware {
            public function process(Request $request, RequestHandler $handler): Response
            {
                $request = $request->withAttribute('msg', 'Hello');
                return $handler->handle($request);
            }
        });
        */
    }

    /**
     * Register Trailing Slash middleware
     * Redirect/rewrite all URLs that end in a / to the non-trailing / equivalent
     * @return void
     */
    public function registerTrailingSlash()
    {
        $this->app->add(new \App\Middleware\TrailingSlashMiddleware($this->container));
    }

    public function registerAuth()
    {
        $this->app->add(new \App\Middleware\AuthMiddleware($this->container));
    }

    /**
     * Register Error middleware
     * Check for HTTP errors on the response and render respective view
     * @return void
     */
    public function registerErrorMiddleware()
    {
        $this->app->add(new \App\Middleware\ErrorMiddleware($this->container));
    }
}
