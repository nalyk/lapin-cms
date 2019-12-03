<?php

namespace App\Controller;

class UserController
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
    public function login($request, $response, $args)
    {
        $language = $request->getAttribute('language');
        $allGetVars = $request->getQueryParams();
        $redirect = null;
        $redirect = @$allGetVars['redirect'];
        
        $data = [];
        $data["redirect"] = $redirect;
        $data["language"] = $language;

        return $this->container->twig->render($response, "@admin/login.html.twig", $data);
    }

    public function auth($request, $response, $args)
    {
        $language = $request->getAttribute('language');
        $allPostPutVars = $request->getParsedBody();
        $username = $allPostPutVars['username'];
        $password = $allPostPutVars['password'];
        $redirect = $allPostPutVars['redirect'];

        $hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost'=>12));
        $data = [];
        $data["language"] = $language;
        if(password_verify($password,$hashed_password)){
            $user = $this->container->deployd->get('users', null, '{"username": "'.$username.'"}');
            $user = $user["data"][0];
            $userRoles = [];
            foreach ($user["roles"] as $role) {
                $userRoles[] = $role["name"];
            }

            $key = "LapinCMS";
            $payload = array(
                "iss"     => "http://lapin-dev.studiotechno.md/",
                "iat"     => time(),
                "exp"     => time() + (60 * 30),
                "context" => [
                    "user" => [
                        "id" => $user["id"],
                        "login" => $username,
                        "lang" => $user["lang"],
                        "ip" => $request->getAttribute('ip_address'),
                        "roles" => $userRoles
                    ]
                ]
            );
            try {
                $jwt = \Firebase\JWT\JWT::encode($payload, $key);
            } catch (Exception $e) {
                echo json_encode($e);
            }
            $response = \Dflydev\FigCookies\FigResponseCookies::set($response, \Dflydev\FigCookies\SetCookie::create('token')
                ->withValue($jwt)
                ->withDomain('lapin-dev.studiotechno.md')
                ->withExpires($payload["exp"])
                ->withPath('/')
            );
            if (null == $redirect) {
                return $response->withRedirect('/'.$language["lang"].'/admin/dashboard', 302);
            } else {
                return $response->withRedirect($redirect, 302);
            }
        }else{
            return $this->container->twig->render($response, "@admin/login.html.twig", $data);
        }
    }
}