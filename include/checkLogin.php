<?php

include_once 'config.php';

if ($userData->isValidToken(@$_POST['token'])) {
    if ($userData->login(@$_POST['user_email'], @$_POST['user_password'])) {
        header('location: ' . BASEDIR);
    } else {
        header('location: ' . BASEDIR . 'loginp');
    }
} else {
    header('location: ' . BASEDIR . 'login?error=Token');
}