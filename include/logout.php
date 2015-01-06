<?php

include_once 'config.php';

if (isset($_GET['token']) && $userData->logout($_GET['token'])) {
    header('location: ' . BASEDIR . 'login?logout=success');
} else {
    header('location: ' . BASEDIR . '?logout=error');
}