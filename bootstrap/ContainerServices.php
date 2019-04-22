<?php

namespace Bootstrap;

class ContainerServices
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
     * Register a service on the container
     * @param  string   $key     service key (for future access)
     * @param  callable $service service
     * @return void
     */
    public function registerService($key, $service)
    {
        if (is_callable($service)) {
            $this->container[$key] = $service;
        } else {
            throw new Exception("Service is not a callable.");
        }
    }

    /**
     * Register all services within the container
     * @return void
     */
    public function registerAllServices()
    {
        /**
         * Register main services
         */
        $this->registerCache();
        $this->registerCsrf();
        $this->registerDatabase();
        $this->registerEloquent();
        $this->registerDeployd();
        $this->registerMailer();
        $this->registerGuzzle();
        $this->registerSerializer();
        $this->registerCarbon();
        $this->registerMonolog();
        $this->registerSession();

        /**
         * Register Twig and View-related services
         */
        $this->registerFaker();
        $this->registerSlugify();
        $this->registerTranslator();
        $this->registerTwig();
        $this->registerUtil();
        $this->registerPsr15();

        /**
         * Register handlers
         */
        $this->registerNotFoundHandler();
    }

    /**
     * Register 'cache' on the container
     * More info on: https://symfony.com/doc/current/components/cache.html
     * @return void
     */
    public function registerCache()
    {
        $this->registerService('cache', function () {
            $namespace       = '';
            $defaultLifetime = 0;
            $directory       = __DIR__ . "/../storage/cache";

            $cache = new \Symfony\Component\Cache\Simple\FilesystemCache($namespace, $defaultLifetime, $directory);

            return $cache;
        });

        $this->registerService('rcache', function () {
            $redisClient = \Symfony\Component\Cache\Adapter\RedisAdapter::createConnection(
                'redis://localhost'
            );
            $namespace       = '';
            $defaultLifetime = 0;

            $rcache = new \Symfony\Component\Cache\Simple\RedisCache($redisClient, $namespace, $defaultLifetime);

            return $rcache;
        });
    }

    /**
     * Register 'csrf' on the container
     * @return void
     */
    public function registerCsrf()
    {
        $this->registerService('csrf', function () {
            $csrf = new \Slim\Csrf\Guard();
            $csrf->setPersistentTokenMode(false);

            return $csrf;
        });
    }

    /**
     * Register 'database' on the container
     * @return void
     */
    public function registerDatabase()
    {
        $this->registerService('database', function () {
            $data = $this->container['settings']['db'];

            $database = new \App\Model\Core\Database(
                "{$data['dbms']}:host={$data['host']};dbname={$data['database']}",
                $data['username'],
                $data['password']
            );

            $database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $database->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $database->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $database->exec("set names utf8");

            return $database;
        });
    }

    /**
     * Register 'eloquent' on the container
     * @return void
     */
    public function registerEloquent()
    {
        $this->registerService('eloquent', function () {
            $data = $this->container['settings']['db'];

            $capsule = new \Illuminate\Database\Capsule\Manager;

            $capsule->addConnection([
                'driver' => $data['dbms'],
                'host' => $data['host'],
                'database' => $data['database'],
                'username' => $data['username'],
                'password' => $data['password'],
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => ''
            ]);

            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
        });
    }

    /**
     * Register 'deployd' on the container
     * @return void
     */
    public function registerDeployd()
    {
        $this->registerService('deployd', function () {
            $deployd = new \App\Model\Core\Deployd($this->container);

            return $deployd;
        });
    }

    /**
     * Register 'mailer' on the container
     * @return void
     */
    public function registerMailer()
    {
        $this->registerService('mailer', function () {
            $mailer = new \App\Model\Core\Mailer($this->container);

            return $mailer;
        });
    }

    /**
     * Register 'guzzle' on the container
     * @return void
     */
    public function registerGuzzle()
    {
        $this->registerService('guzzle', function () {
            $guzzle = new \GuzzleHttp\Client();

            return $guzzle;
        });
    }

    /**
     * Register 'serializer' on the container
     * @return void
     */
    public function registerSerializer()
    {
        $this->registerService('serializer', function () {
            $serializer = new \Zumba\JsonSerializer\JsonSerializer();

            return $serializer;
        });
    }

    /**
     * Register 'carbon' on the container
     * @return void
     */
    public function registerCarbon()
    {
        $this->registerService('carbon', function () {
            $locale = $this->container['settings']['locale'];
            $carbon = new \Jenssegers\Date\Date;
            $carbon->setLocale('ro');

            return $carbon;
        });
    }

    /**
     * Register 'monolog' on the container
     * @return void
     */
    public function registerMonolog()
    {
        $this->registerService('monolog', function () {
            $logfile =__DIR__ . "/../storage/logs/application.log";

            $monolog = new \Monolog\Logger('application');
            $fileHandler = new \Monolog\Handler\StreamHandler($logfile);

            $monolog->pushHandler($fileHandler);
            $monolog->pushProcessor(new \Monolog\Processor\WebProcessor);
            $monolog->pushProcessor(new \Monolog\Processor\IntrospectionProcessor);

            return $monolog;
        });
    }

    /**
     * Register 'session' on the container
     * @return void
     */
    public function registerSession()
    {
        $this->registerService('session', function () {
            $session = new \SlimSession\Helper;

            return $session;
        });
    }

    /**
     * Register 'faker' on the container
     * @return void
     */
    public function registerFaker()
    {
        $this->registerService('faker', function () {
            $faker = \Faker\Factory::create();

            return $faker;
        });
    }

    /**
     * Register 'slugify' on the container
     * @return void
     */
    public function registerSlugify()
    {
        $this->registerService('slugify', function () {
            $slugify = new \Cocur\Slugify\Slugify();

            return $slugify;
        });
    }

    /**
     * Register 'translator' on the container
     * @return void
     */
    public function registerTranslator()
    {
        $this->registerService('translator', function () {
            $translator = new \App\Helper\Translator($this->container);

            return $translator;
        });
    }

    /**
     * Register 'twig' on the container
     *
     * Depends on:
     * $this->registerCsrf()
     *
     * @return void
     */
    public function registerTwig()
    {
        $this->registerService('twig', function () {
            $paths = [
                'templates' => __DIR__ . "/../web/templates",
                'cache'     => __DIR__ . "/../storage/cache"
            ];

            $twig = new \Slim\Views\Twig($paths['templates'], [
                'cache' => ($this->container->settings['environment'] == 'production') ? $paths['cache'] : false,
                'debug' => ($this->container->settings['environment'] == 'production') ? false : true
            ]);

            /**
            * Debug features
            */
            $twig->addExtension(new \Twig_Extension_Debug());

            /**
            * Slim integration
            */
            $basePath = rtrim(str_ireplace('index.php', '', $this->container['request']->getUri()->getBasePath()), '/');
            $twig->addExtension(new \Slim\Views\TwigExtension($this->container['router'], $basePath));

            /**
            * Slugify library
            * https://github.com/cocur/slugify
            */
            $twig->addExtension(new \Cocur\Slugify\Bridge\Twig\SlugifyExtension(\Cocur\Slugify\Slugify::create()));

            /**
            * Twig Extensions
            * https://github.com/twigphp/Twig-extensions
            */
            $twig->addExtension(new \Twig_Extensions_Extension_Text());
            $twig->addExtension(new \Twig_Extensions_Extension_Intl());
            $twig->addExtension(new \Twig_Extensions_Extension_Date());

            /**
            * Twig Extensions
            * https://github.com/twigphp/Twig-extensions
            */
           
            $twig->addExtension(new \Odan\Twig\TwigAssetsExtension($twig->getEnvironment(), $this->container->settings['TWIG_PATH']));

            /**
            * Custom tests
            */

            /**
            * Custom test: check if data is array
            *
            * Usage:
            * {% if foo is array %}
            */
            $twig->getEnvironment()->addTest(new \Twig_SimpleTest('array', function ($value) {
                return is_array($value);
            }));

            /**
            * Global variables (available to all templates)
            */

            /**
            * Global variables
            * Current URL and URI
            */
            $url = $this->container->get('request')->getUri();

            if (substr($url, -1) == '/') {
                $url = substr($url, 0, -1);
            }

            $twig->getEnvironment()->addGlobal('currentUrl', $url);
            $twig->getEnvironment()->addGlobal('currentUri', $this->container->get('request')->getUri()->getPath());

            $twig->getEnvironment()->addGlobal('domain', $this->container->get('settings')['domain']);

            /**
            * Global variables
            * CSRF protection (by Slim-CSRF)
            */
            $csrf                  = array();
            $csrf['keys']['name']  = $this->container->csrf->getTokenNameKey();
            $csrf['keys']['value'] = $this->container->csrf->getTokenValueKey();
            $csrf['name']          = $this->container->csrf->getTokenName();
            $csrf['value']         = $this->container->csrf->getTokenValue();

            $twig->getEnvironment()->addGlobal('csrf', $csrf);

            /**
            * Custom functions
            */

            /**
            * Custom function: get translated string by its identifier and language
            *
            * Usage:
            * {{ translator('key') }}
            * {{ translator('key', {'foo': 'bar'}) }}
            *
            * More info on App\Helper\Translator class
            */
            $translatorFunction = new \Twig_Function('translator', array(new Twig\CustomFunction\Translator($this->container), 'index'));
            $twig->getEnvironment()->addFunction($translatorFunction);

            /**
            * Custom Twig Path Namespace
            */

            /**
            * Custom Twig Path Namespace to use in "render" controllers
            *
            * Usage:
            * return $this->container->twig->render($response, "@site/index.html.twig", $data);
            * return $this->container->twig->render($response, "@admin/index.html.twig", $data);
            *
            */
            $twig->getEnvironment()->getLoader()->addPath($paths['templates'].'/admin-theme','admin');
            $twig->getEnvironment()->getLoader()->addPath($paths['templates'].'/site-theme','site');

            return $twig;
        });
    }

    /**
     * Register 'util' on the container
     * @return void
     */
    public function registerUtil()
    {
        $this->registerService('util', function () {
            $util = new \App\Helper\Util();

            return $util;
        });
    }

    /**
     * Register 'callableResolver' (psr-15) on the container
     * @return void
     */
    public function registerPsr15()
    {
        $this->registerService('callableResolver', function () {
            $callableResolver = new \Bnf\Slim3Psr15\CallableResolver($this->container);

            return $callableResolver;
        });
    }

    /**
     * Register 'notFoundHandler' on the container
     * @return void
     */
    public function registerNotFoundHandler()
    {
        $this->registerService('notFoundHandler', function () {
            return new \App\Handler\NotFound($this->container, function ($request, $response) {
                return $this->container['response']->withStatus(404);
            });
        });
    }
}
