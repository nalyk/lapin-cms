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
        $this->slugify = new \Cocur\Slugify\Slugify(['rulesets' => ['romanian', 'russian', 'default']]);
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
        /* common variables */
        $language = $request->getAttribute('language');
        if (!$request->getAttribute('auth')["_status"]) { return $response->withRedirect('/'.$language["lang"].'/login?redirect='.$request->getUri()->getPath(), 302); }
 
        $cms = [];
        $cms["types"] = $this->getTypes();
        $cms["categories"] = $this->getCategories();

        $data = [];
        $data["auth"] = $request->getAttribute('auth');
        $data["cms"] = $cms;
        $data["language"] = $language;
        $user = @$this->container->deployd->get('users', $request->getAttribute('auth')["_user"]->id, null);
        $data["user"] = $user["data"];
        /* /END common variables */

        return $this->container->twig->render($response, "@admin/index.html.twig", $data);
    }

    public function types($request, $response, $args)
    {
        /* common variables */
        $language = $request->getAttribute('language');
        if (!$request->getAttribute('auth')["_status"]) { return $response->withRedirect('/'.$language["lang"].'/login?redirect='.$request->getUri()->getPath(), 302); }
 
        $cms = [];
        $cms["types"] = $this->getTypes();
        $cms["categories"] = $this->getCategories();

        $data = [];
        $data["auth"] = $request->getAttribute('auth');
        $data["cms"] = $cms;
        $data["language"] = $language;
        $user = @$this->container->deployd->get('users', $request->getAttribute('auth')["_user"]->id, null);
        $data["user"] = $user["data"];
        /* /END common variables */

        $collection = null;
        $collection = $args["name"];
        
        $data["collection"] = $collection;
        $data["activeType"] = "type";
        $data["activeCollection"] = $collection;

        return $this->container->twig->render($response, "@admin/admin-types.html.twig", $data);
    }

    public function categories($request, $response, $args)
    {
        /* common variables */
        $language = $request->getAttribute('language');
        if (!$request->getAttribute('auth')["_status"]) { return $response->withRedirect('/'.$language["lang"].'/login?redirect='.$request->getUri()->getPath(), 302); }
 
        $cms = [];
        $cms["types"] = $this->getTypes();
        $cms["categories"] = $this->getCategories();

        $data = [];
        $data["auth"] = $request->getAttribute('auth');
        $data["cms"] = $cms;
        $data["language"] = $language;
        $user = @$this->container->deployd->get('users', $request->getAttribute('auth')["_user"]->id, null);
        $data["user"] = $user["data"];
        /* /END common variables */

        $collection = null;
        $collection = @$args["name"];

        $data["parrentCategories"] = $this->getCmsMainCategories();
        $data["collection"] = $collection;
        $data["activeType"] = "category";
        $data["activeCollection"] = "all";

        return $this->container->twig->render($response, "@admin/admin-categories.html.twig", $data);
    }

    public function articles($request, $response, $args)
    {
        /* common variables */
        $language = $request->getAttribute('language');
        if (!$request->getAttribute('auth')["_status"]) { return $response->withRedirect('/'.$language["lang"].'/login?redirect='.$request->getUri()->getPath(), 302); }
 
        $cms = [];
        $cms["types"] = $this->getTypes();
        $cms["categories"] = $this->getCategories();

        $data = [];
        $data["auth"] = $request->getAttribute('auth');
        $data["cms"] = $cms;
        $data["language"] = $language;
        $user = @$this->container->deployd->get('users', $request->getAttribute('auth')["_user"]->id, null);
        $data["user"] = $user["data"];
        $ismobile = $request->isMobile() ? true : false;
        $data["ismobile"] = $ismobile;
        /* /END common variables */

        $data["parrentCategories"] = $this->getCmsMainCategories();
        $data["activeType"] = "article";

        return $this->container->twig->render($response, "@admin/admin-articles.html.twig", $data);
    }

    public function articlesCreate($request, $response, $args)
    {
        /* common variables */
        $language = $request->getAttribute('language');
        if (!$request->getAttribute('auth')["_status"]) { return $response->withRedirect('/'.$language["lang"].'/login?redirect='.$request->getUri()->getPath(), 302); }
 
        $cms = [];
        $cms["types"] = $this->getTypes();
        $cms["categories"] = $this->getCategories();

        $data = [];
        $data["auth"] = $request->getAttribute('auth');
        $data["cms"] = $cms;
        $data["language"] = $language;
        $user = @$this->container->deployd->get('users', $request->getAttribute('auth')["_user"]->id, null);
        $data["user"] = $user["data"];
        $ismobile = $request->isMobile() ? true : false;
        $data["ismobile"] = $ismobile;
        /* /END common variables */

        $data["parrentCategories"] = $this->getCmsMainCategories();
        $data["activeType"] = "article";

        return $this->container->twig->render($response, "@admin/admin-articles-create.html.twig", $data);
    }

    public function ajaxTypes($request, $response, $args)
    {
        $language = $request->getAttribute('language');

        $method = $request->getMethod();
        $collection = $args['collection'];
        $accepted = $this->getTypes();
        $data = [];

        if ($method == "GET") {
            if (in_array($collection, $accepted, TRUE)) {
                $data["_status"] = true;
                $items = $this->container->deployd->get('kinds', null, '{"collection": "'.$collection.'"}');
                $data["items"] = $items["data"];
                $data["type"] = "type";
                $data["title"] = $collection;
            } else {
                $data["_status"] = false;
                $data["_message"] = "Accepted content types are: ".implode(", ",$accepted);
            }
        } elseif ($method == "PUT") {
            if (in_array($collection, $accepted, TRUE)) {
                $allPostPutVars = $request->getParsedBody();
                
                $typeId = $allPostPutVars['id'];
                $typeName = $allPostPutVars['name'];
                $typeDescription = $allPostPutVars['description'];

                $updateData["collection"] = $collection;
                $updateData["name"] = $typeName;
                $updateData["description"] = $typeDescription;
                $item = $this->container->deployd->put('kinds', $typeId, $updateData);
                $data["item"] = $item["data"];
                $data["type"] = "type";
                $data["title"] = $collection;
                $data["_status"] = true;
            } else {
                $data["_status"] = false;
                $data["_message"] = "Accepted content types are: ".implode(", ",$accepted);
            }
        } elseif ($method == "POST") {
            if (in_array($collection, $accepted, TRUE)) {
                $allPostPutVars = $request->getParsedBody();
                
                $typeName = $allPostPutVars['name'];
                $typeDescription = $allPostPutVars['description'];

                $insertData["name"] = $typeName;
                $insertData["description"] = $typeDescription;
                $insertData["collection"] = $collection;
                $item = $this->container->deployd->post('kinds', $insertData);
                $data["item"] = $item["data"];
                $data["type"] = "type";
                $data["title"] = $collection;
                $data["_status"] = true;
            } else {
                $data["_status"] = false;
                $data["_message"] = "Accepted content types are: ".implode(", ",$accepted);
            }
        } elseif ($method == "DELETE") {
            if (in_array($collection, $accepted, TRUE)) {
                $allPostPutVars = $request->getParsedBody();

                $typeId = $allPostPutVars['id'];

                $item = $this->container->deployd->del('kinds', $typeId);
                $data["item"] = $item["data"];
                $data["type"] = "type";
                $data["title"] = $collection;
                $data["_status"] = true;
            } else {
                $data["_status"] = false;
                $data["_message"] = "Accepted content types are: ".implode(", ",$accepted);
            }
        } else {
            $data["_status"] = false;
            $data["_message"] = "METHOD not accepted";
        }
        
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($data));
    }

    public function ajaxCategories($request, $response, $args)
    {
        $method = $request->getMethod();
        $collection = @$args['collection'];
        $accepted = $this->getCategories();
        $data = [];

        if ($method == "GET") {
            $data["_status"] = true;
            $items = $this->container->deployd->get('categories', null, null);
            $data["items"] = $items["data"];
            $data["type"] = "category";
            $data["title"] = "all";
        } elseif ($method == "PUT") {
            $allPostPutVars = $request->getParsedBody();
            $categoryName = $allPostPutVars['name'];
            $categoryId = $allPostPutVars['id'];
            $categoryDescription = $allPostPutVars['description'];
            $categoryCollections = [];
            $categoryCollections = @$allPostPutVars['collections'];

            $categoryVisible = $allPostPutVars['visible'];
            $categoryParrent = null;
            $categoryParrent = $allPostPutVars['parrent'];

            $updateData["name"] = $categoryName;
            $updateData["description"] = $categoryDescription;
            $updateData["collections"] = $categoryCollections;
            $updateData["visible"] = $categoryVisible;
            if (null !== $categoryParrent) {
                $updateData["parrent"] = $categoryParrent;
            }

            $item = $this->container->deployd->put('categories', $categoryId, $updateData);

            $data["item"] = $item["data"];
            $data["type"] = "category";
            $data["title"] = "all";
            $data["_status"] = true;
        } elseif ($method == "POST") {

            $allPostPutVars = $request->getParsedBody();
            
            $categoryName = $allPostPutVars['name'];
            $categoryDescription = $allPostPutVars['description'];
            $categoryCollections = $allPostPutVars['collections'];
            $categoryVisible = $allPostPutVars['visible'];
            $categoryParrent = null;
            $categoryParrent = $allPostPutVars['parrent'];

            $insertData["name"] = $categoryName;
            $insertData["description"] = $categoryDescription;
            $insertData["collections"] = $categoryCollections;
            $insertData["visible"] = $categoryVisible;
            if (null !== $categoryParrent) {
                $insertData["parrent"] = $categoryParrent;
            }


            $item = $this->container->deployd->post('categories', $insertData);
            $data["item"] = $item["data"];
            $data["type"] = "category";
            $data["title"] = "all";
            $data["_status"] = true;
        } elseif ($method == "DELETE") {
            $allPostPutVars = $request->getParsedBody();

            $categoryId = $allPostPutVars['id'];

            $item = $this->container->deployd->del('categories', $categoryId);
            $data["item"] = $item["data"];
            $data["type"] = "category";
            $data["title"] = "all";
            $data["_status"] = true;
        } else {
            $data["_status"] = false;
            $data["_message"] = "METHOD not accepted";
        }
        
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($data));
    }

    public function ajaxArticles($request, $response, $args)
    {
        $method = $request->getMethod();
        $collection = @$args['id'];
        $accepted = $this->getCategories();
        $data = [];

        if ($method == "GET") {
            $data["_status"] = true;
            $items = $this->container->deployd->get('articles', null, null);
            $data["items"] = $items["data"];
            $data["type"] = "article";
            $data["title"] = "all";
        } elseif ($method == "PUT") {
            $allPostPutVars = $request->getParsedBody();
            /*
            $categoryName = $allPostPutVars['name'];
            $categoryId = $allPostPutVars['id'];
            $categoryDescription = $allPostPutVars['description'];
            $categoryCollections = [];
            $categoryCollections = @$allPostPutVars['collections'];

            $categoryVisible = $allPostPutVars['visible'];
            $categoryParrent = null;
            $categoryParrent = $allPostPutVars['parrent'];

            $updateData["name"] = $categoryName;
            $updateData["description"] = $categoryDescription;
            $updateData["collections"] = $categoryCollections;
            $updateData["visible"] = $categoryVisible;
            if (null !== $categoryParrent) {
                $updateData["parrent"] = $categoryParrent;
            }

            $item = $this->container->deployd->put('categories', $categoryId, $updateData);

            $data["item"] = $item["data"];
            $data["type"] = "category";
            $data["title"] = "all";
            */
            $data["_status"] = true;
        } elseif ($method == "POST") {
            $allPostPutVars = $request->getParsedBody();
            /*
            $categoryName = $allPostPutVars['name'];
            $categoryDescription = $allPostPutVars['description'];
            $categoryCollections = $allPostPutVars['collections'];
            $categoryVisible = $allPostPutVars['visible'];
            $categoryParrent = null;
            $categoryParrent = $allPostPutVars['parrent'];

            $insertData["name"] = $categoryName;
            $insertData["description"] = $categoryDescription;
            $insertData["collections"] = $categoryCollections;
            $insertData["visible"] = $categoryVisible;
            if (null !== $categoryParrent) {
                $insertData["parrent"] = $categoryParrent;
            }


            $item = $this->container->deployd->post('categories', $insertData);
            $data["item"] = $item["data"];
            $data["type"] = "category";
            $data["title"] = "all";
            */
            $data["_status"] = true;
        } elseif ($method == "DELETE") {
            $allPostPutVars = $request->getParsedBody();
            /*
            $categoryId = $allPostPutVars['id'];

            $item = $this->container->deployd->del('categories', $categoryId);
            $data["item"] = $item["data"];
            $data["type"] = "category";
            $data["title"] = "all";
            */
            $data["_status"] = true;
        } else {
            $data["_status"] = false;
            $data["_message"] = "METHOD not accepted";
        }
        
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($data));
    }

    public function ajaxModal($request, $response, $args)
    {
        $method = $request->getMethod();
        $name = @$args['name'];
        $language = @$args['lang'];

        $cms = [];
        $cms["types"] = $this->getTypes();
        $cms["categories"] = $this->getCategories();

        $data = [];
        $data["language"] = $language;
        $data["cms"] = $cms;

        if ($method == "GET") {
            $allGetVars = $request->getQueryParams();
            $modal_id = $allGetVars["ajaxModalId"];
            $data["modalId"] = $modal_id;

            if ($name == "category") {
                $data["parrentCategories"] = $this->getCmsMainCategories();
            }

            return $this->container->twig->render($response, "@admin/inc/ajax/modal_".$name.".html.twig", $data);
        }
    }

    public function ajaxUploadImage($request, $response, $args)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedImage = $uploadedFiles['file'];


        if ($uploadedImage->getError() === UPLOAD_ERR_OK) {

            $filename = $uploadedImage->getClientFilename();
            $prefolder = date("Y-m");
            $timestamp = time();

            $uploadURI = $prefolder."/".$timestamp."-".$this->slugify->slugify($filename);

            $upload = $this->container->minio->putObject([
                'Bucket' => 'yoda',
                'Key'    => $uploadURI,
                'SourceFile' => $uploadedImage->file
            ]);

            $plainUrl = $this->container->minio->getObjectUrl('yoda', $uploadURI);

            $data["_status"] = true;
            $data["filename"] = $filename;
            $data["plainUrl"] = $plainUrl;
        } else {
            $data["_status"] = false;
        }
        
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($data));
    }

    public function getTypes()
    {
        $cms = $this->container['settings']['cms'];
        $d = dir($cms['resources']);
        $types = array();
        while (false !== ($entry = $d->read())) {
            if (($entry !== '.') && ($entry !== '..') && (!in_array($entry, $cms['types']['ignore'])) ) {
                $types[] = $entry;
            }
        }
        $d->close();
        return $types;
    }

    public function getCategories()
    {
        $cms = $this->container['settings']['cms'];
        $d = dir($cms['resources']);
        $categories = array();
        while (false !== ($entry = $d->read())) {
            if (($entry !== '.') && ($entry !== '..') && (!in_array($entry, $cms['categories']['ignore'])) ) {
                $categories[] = $entry;
            }
        }
        $d->close();
        return $categories;
    }

    public function getCmsMainCategories()
    {
        $items = @$this->container->deployd->get('categories', null, '{"parrent": "null"}');
        return $items["data"];
    }

}
