<?php

namespace App\Controller;

class DeploydController
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
        //$news = $this->container->deployd->get("news", null, '{"category":"sport"}');
        $news["msg"] = $request->getAttribute('msg');
        $data = ['news' => $news];
        return $this->container->twig->render($response, "index.html.twig", $data);
    }

    /**
     * This method is called when the user enters the `/deployd/new` route
     * @param  \Psr\Http\Message\ServerRequestInterface $request   PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response  PSR7 response
     * @param  array                                    $args      Route parameters
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function new($request, $response, $args)
    {
        //$news = $this->container->deployd->get("news", null, '{"category":"sport"}');
        $news["msg"] = $request->getAttribute('msg');
        $data = ['news' => $news];
        return $this->container->twig->render($response, "index.html.twig", $data);
    }

    /**
     * This method is called when the user enters the `/deployd/create` route
     * @param  \Psr\Http\Message\ServerRequestInterface $request   PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response  PSR7 response
     * @param  array                                    $args      Route parameters
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create($request, $response, $args)
    {
        //$news = $this->container->deployd->get("news", null, '{"category":"sport"}');
        $news["msg"] = $request->getAttribute('msg');
        $data = ['news' => $news];
        return $this->container->twig->render($response, "index.html.twig", $data);
    }

    /**
     * This method is called when the user enters the `/deployd/update` route
     * @param  \Psr\Http\Message\ServerRequestInterface $request   PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response  PSR7 response
     * @param  array                                    $args      Route parameters
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update($request, $response, $args)
    {
        //$news = $this->container->deployd->get("news", null, '{"category":"sport"}');
        $news["msg"] = $request->getAttribute('msg');
        $data = ['news' => $news];
        return $this->container->twig->render($response, "index.html.twig", $data);
    }

    /**
     * This method is called when the user enters the `/deployd/edit/{id}` route
     * @param  \Psr\Http\Message\ServerRequestInterface $request   PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response  PSR7 response
     * @param  array                                    $args      Route parameters
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function edit($request, $response, $args)
    {
        //$news = $this->container->deployd->get("news", null, '{"category":"sport"}');
        $news["msg"] = $request->getAttribute('msg');
        $data = ['news' => $news];
        return $this->container->twig->render($response, "index.html.twig", $data);
    }

    /**
     * This method is called when the user enters the `/deployd/delete/{id}` route
     * @param  \Psr\Http\Message\ServerRequestInterface $request   PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response  PSR7 response
     * @param  array                                    $args      Route parameters
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete($request, $response, $args)
    {
        //$news = $this->container->deployd->get("news", null, '{"category":"sport"}');
        $news["msg"] = $request->getAttribute('msg');
        $data = ['news' => $news];
        return $this->container->twig->render($response, "index.html.twig", $data);
    }

}
