<?php

define('__VENDOR__', __DIR__.'/../vendor');

// See https://github.com/fabpot/Silex/pull/67
require 'phar://'.__VENDOR__.'/Silex/silex.phar/autoload.php';

$app = new Silex\Application();

/** bootstrap **/
$app['autoloader']->registerNamespaces(array(
    'Predis\Silex' => __VENDOR__.'/PredisServiceProvider/lib',
));

$app->register(new Predis\Silex\PredisServiceProvider(), array(
    'predis.class_path' => __VENDOR__.'/Predis/lib',
    'predis.parameters' => 'tcp://127.0.0.1:6379/',
    'predis.options'    => array(
        'profile' => '2.2', 
        'prefix'  => 'silex:',
    ),
));

/** routes **/
$app->get('/', function() use($app) {
    return var_export($app['predis']->info(), true);
});

/** run application **/
$app->run();
