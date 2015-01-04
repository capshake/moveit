<?php
include_once '../include/config.php';
if (!$userData->isLoggedIn()) {
    header('location: '.BASEDIR.'index.php');
} else {
    include_once '../include/header.php';
    ?>

   jooo
    <?php
    include_once '../include/footer.php';
}
