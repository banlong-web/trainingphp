<?php
session_start();
require_once './mvc/App.php';
require_once './mvc/Controllers.php';
require_once './mvc/connectDB.php';


define('UPLOAD_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/trainingphp/mvc/public/uploads/');
define('URL_BASE', 'http://'.$_SERVER['SERVER_NAME']. '/trainingphp/mvc/public/uploads/');
define('URL_SITE', 'http://'.$_SERVER['SERVER_NAME']. '/trainingphp');
$app = new App();
