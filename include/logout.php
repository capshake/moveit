<?php
include_once 'config.php';

header('location: '.BASEDIR.'pages/main/login.php');

if($userData->logout()) {
    echo 'ok';
} else {
    echo 'error';
}