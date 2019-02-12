<?php

namespace App\Controller;

class AdminController
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
     * This method is called when the user enters the `/admin/dashboard` route
     * @param  \Psr\Http\Message\ServerRequestInterface $request   PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response  PSR7 response
     * @param  array                                    $args      Route parameters
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dashboard($request, $response, $args)
    {
        $this->logger->warning(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);
        //$news = $this->container->deployd->get("news", null, '{"category":"sport"}');
        //$news["msg"] = $request->getAttribute('msg');
        $news = null;
        $data = ['news' => $news];
        return $this->container->twig->render($response, "admin-theme/index.html.twig", $data);
    }

}
