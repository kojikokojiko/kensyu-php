<?php

use App\App;

require_once __DIR__.'/../vendor/autoload.php';

// PHPセッション設定の変更
ini_set('session.cookie_samesite', 'Lax');
//ini_set('session.cookie_secure', 'true'); // HTTPSの場合のみ必要
ini_set('session.cookie_httponly', 'true');

session_start();
$app=new App();
$app->run();


