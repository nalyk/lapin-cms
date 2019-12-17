<?php

namespace App\Middleware;

class LanguageMiddleware {

    protected $container;

    public function __construct($available_languages, $default_language, $container) {

        if(!is_array($available_languages)) {
            $available_languages = array($available_languages);
        }

        $this->container = $container;
        $this->default_language = $default_language;
        $this->available_languages = $available_languages;
        /*
        $this->container['default_language'] = $default_language;
        $this->container['available_languages'] = $available_languages;
        $this->container['language'] = $default_language;
        $this->container['translations'] = null;
        */
   }

    /**
     * RestrictRoute middleware invokable class.
     * 
     * @param \Psr\Http\Message\ServerRequestInterface PSR7 request
     * @param \Psr\Http\Message\ResponseInterface PSR7 response
     * @param callable 
     * 
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next) {

        $uri = $request->getUri();
        $path = $uri->getPath();
        $route_lang = null;
        
        $route = @$request->getAttribute('route');
        if ($route) {
            $route_lang = @$route->getArgument('lang');
        }
        

        if (null == $route_lang) {
            $language["lang"] = $this->default_language;
        } else {
            $language["lang"] = $route_lang;
        }

        $translations = $this->container->settings['cms']['languages']['avaliable'];
        $current_language = $language["lang"];
        $lang_index = array_search($current_language, $translations);
        if($lang_index !== false){
            unset($translations[$lang_index]);
        }
        $language["translations"] = $translations;
       
        $newRequest = $request->withAttribute('language', $language);
        $response = $next($newRequest, $response);
        
        return $response;
    }
}