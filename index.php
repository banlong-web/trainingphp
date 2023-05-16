<?php
session_start();
require_once './mvc/App.php';
require_once './mvc/Controllers.php';
require_once './mvc/connectDB.php';

define('UPLOAD_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/trainingphp/mvc/public/uploads/');
define('URL_BASE', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']. '/trainingphp/mvc/public/uploads/');
define('URL_SITE', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']. '/trainingphp');
$app = new App();
