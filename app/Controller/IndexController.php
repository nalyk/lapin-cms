<?php

namespace App\Controller;

class IndexController
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

    /**
     * This method is called when the user enters the `/` route
     * @param  \Psr\Http\Message\ServerRequestInterface $request   PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response  PSR7 response
     * @param  array                                    $args      Route parameters
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index($request, $response, $args)
    {
        $this->logger->warning(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);
        /* common variables */
        $language = $request->getAttribute('language');
         
        $cms = [];
        //$cms["types"] = \App\Controller\AdminController::getTypes();
        //$cms["categories"] = \App\Controller\AdminController::getCategories();

        $data = [];
        $data["auth"] = @$request->getAttribute('auth');
        $data["cms"] = $cms;
        $data["language"] = $language;
        $user = @$this->container->deployd->get('users', $request->getAttribute('auth')["_user"]->id, null);
        $data["user"] = $user["data"];
        /* /END common variables */


        return $this->container->twig->render($response, "@site/index.html.twig", $data);
    }
}
