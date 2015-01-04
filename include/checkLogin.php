<?php
include_once 'config.php';

if($csrfToken->isValidToken(@$_POST['token'])) {
    if($userData->login(@$_POST['user_email'], @$_POST['user_password'])) {
        header('location: '.BASEDIR.'index.php');
    } else {
        header('location: '.BASEDIR.'/pages/main/login.php');
    }
} else {
    header('location: '.BASEDIR.'/pages/main/login.php?error=Token');
}