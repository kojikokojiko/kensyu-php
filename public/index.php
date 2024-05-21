<?php

use App\App;

require_once __DIR__.'/../vendor/autoload.php';
session_start();
$app=new App();
$app->run();


