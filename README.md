# Lapin CMS

This is a CMS based on Slim Framework (with MVC pattern), Deployd for the backend API and third-party libraries.

**Documentation is still in progress. Hold on.**

## Directory structure
- `app` - contains your application files
  - `Controller`  - contains your Controllers classes
  - `Handler` - contains your Handlers classes - like [Error Handlers](https://www.slimframework.com/docs/handlers/error.html)
  - `Helper` - contains your Helper classes
  - `Middleware` - contains your Middleware classes
  - `Model` - contains your Model classes
    - `Core` - contains Model classes fundamental for your application
- `bootstrap` - contains the files that will bootstrap your application (make it run)
  - `Twig` - contains your Twig-related classes like custom functions, custom tests, custom extensions and more
    - `CustomFunction` - contains your custom Twig functions
- `database` - contains your migrations and seeds provided by [Phinx](https://phinx.org)
- `lang` - constains your language strings provided by [Laravel Localization](https://laravel.com/docs/5.5/localization)
- `public` - contains the starting point (`index.php`). You should store your front-end production assets (CSS, JS, Images and Fonts) on this directory
- `storage` - used by the boilerplate to store Cache and Logs (must be **writable**)
- `web` - contains your front-end related files
  - `templates` - contains your templates provided by [Twig](https://twig.symfony.com)

## php -S localhost:8080 -t public public/index.php

## Routes
### Deployd Objects
- /deployd/new - GET - new object form
- /deployd/create - POST - new object
- /deployd/update - PUT - update object
- /deployd/edit/{id} - GET - edit object form
- /deployd/delete/{id} - DELETE - delete object

## Added libs
- https://github.com/DavidePastore/Slim-Validation
- https://github.com/nahid/jsonq 
- https://github.com/zumba/json-serializer
- https://github.com/PHLAK/Twine
- https://github.com/jenssegers/date
    - echo $this->container->carbon->now()->format('l j F Y H:i:s');
- https://github.com/ramsey/uuid
- https://github.com/zguillez/slim-mobile-detect
    - $request->isMobile()
    - $request->isTablet()
    - $request->isAndroidOS()
- https://github.com/jaybizzle/crawler-detect
- https://github.com/bnf/slim3-psr15
- https://github.com/mikehaertl/php-shellcommand
- https://github.com/twig/extensions
- https://github.com/facebook/graph-sdk


## Libs to watch / install later
- https://github.com/jwage/purl
- https://github.com/jobbyphp/jobby