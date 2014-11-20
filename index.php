<?php

require 'vendor/autoload.php';

/**
 * Like example
 */
$like = new Something\Like('blagbla83eq33b3r', 235);

$stubborn = new Stubborn\Stubborn($like);
$result = $stubborn->run();

var_dump($result);


/**
 * Like example
 */
$upload = new Something\Upload();

$stubborn = new Stubborn\Stubborn($upload);
$result = $stubborn->run();

var_dump($result);
