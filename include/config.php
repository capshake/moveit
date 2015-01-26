<?php
session_start();

require_once "functions.php";

require_once "classes/db.class.php";
require_once "classes/user.class.php";
//require("classes/token.class.php");

//$csrfToken = new Token();
$db = new Db();
$userData = new User();

define('BASEURL', 'http://localhost');
define('BASEDIR', '/moveit/');
define('ROOTDIR', $_SERVER['DOCUMENT_ROOT'].BASEDIR);

define('SALT', '5be7a9ef0399b5c7d9a303d75b0711866b8de92d723d5fab698b2426a4c8e12f');


define('TABLE_USERS', 'users');
define('TABLE_ROLES', 'roles');
define('TABLE_BUILDINGS', 'buildings');
define('TABLE_MAPS', 'maps');
define('TABLE_ROOMS', 'rooms');
define('TABLE_USER_ROOMS', 'user_role_room');


define('TABLE_ITEMS', 'items');
define('TABLE_IMPORT', 'data_import');
define('TABLE_EXPORT', 'data_export');
define('TABLE_DEPARTMENTS', 'departments');