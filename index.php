<?php
require 'vendor/Slim/Slim.php';

$app = new Slim();

$app->get('/', function() {
    echo '<h1>Hello Slim World</h1>';
});

$app->run();