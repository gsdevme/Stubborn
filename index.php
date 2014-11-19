<?php

$root = realpath(__DIR__) . '/';

spl_autoload_register(function($class) use ($root){
    $file = $root . str_replace('\\', '/', $class) . '.php';

    if(file_exists($file)){
        require $file;
    }
}, true, true);

/**
 * Like example
 */
$like = new Something\Like('blagbla83eq33b3r', 235);

$stubborn = new \Stubborn\Stubborn($like);
$result = $stubborn->run();

var_dump($result);


/**
 * Like example
 */
$upload = new Something\Upload();

$stubborn = new \Stubborn\Stubborn($upload);
$result = $stubborn->run();

var_dump($result);
