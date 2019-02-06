<?php

require __DIR__ . '/vendor/autoload.php';
$climate = new \League\CLImate\CLImate;
if (PHP_SAPI == 'cli') {
    /*
    $argv = $GLOBALS['argv'];
    array_shift($argv);
    $pathInfo = implode('/', $argv);

    $env = \Slim\Http\Environment::mock(['PATH_INFO' => $pathInfo]);
    echo "test";
    */
    
    $climate->out('This prints to the terminal.');

}
?>