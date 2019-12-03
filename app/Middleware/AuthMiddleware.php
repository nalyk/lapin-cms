<?php

namespace App\Middleware;

class AuthMiddleware
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
     * Middleware processing
     * Redirect/rewrite all URLs that end in a / to the non-trailing / equivalent
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $token = null;
        $cookie = \Dflydev\FigCookies\FigRequestCookies::get($request, 'token')->getValue();

        $token = @$cookie;
        $auth = [];

        if (null !== $token) {
            $key = "LapinCMS";
            $decoded = \Firebase\JWT\JWT::decode($token, $key, array('HS256'));
            $auth["_status"] = true;
            $auth["_user"] = $decoded->context->user;
            $auth["_expire"] = $decoded->exp;
            $response = $response->withAddedHeader('token', $token);
        } else {
            $auth["_status"] = false;
            $auth["_msg"] = "User not logged in";
        }

        $newRequest = $request->withAttribute('auth', $auth);
        $response = $next($newRequest, $response);
        
        return $response;
    }
}
