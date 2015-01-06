<?php
session_start();

require("classes/db.class.php");
require("classes/user.class.php");
//require("classes/token.class.php");

//$csrfToken = new Token();
$db = new Db();
$userData = new User();

define('BASEURL', 'http://localhost');
define('BASEDIR', '/moveit/');
define('SALT', '5be7a9ef0399b5c7d9a303d75b0711866b8de92d723d5fab698b2426a4c8e12f');


define('TABLE_USERS', 'users');
define('TABLE_ROLES', 'roles');

/*$host      = 'localhost';
$user      = 'root';
$pass      = '';
$dbname    = 'moveit';


try{
    $db = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $user, $pass);
}
// Catch any errors
catch(PDOException $e){
    $this->error = $e->getMessage();
}*/
