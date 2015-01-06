<?php
include_once '../../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    $headerTitle = 'Adminpanel - Benutzer';

    include_once '../../include/header.php';
    include_once '../include/menu.php';
    ?>


    <div class="container">

        <div class="starter-template">
            <h1>Benutzer</h1>
            <p class="lead">Adminpanel.</p>


        </div>

    </div><!-- /.container -->

    <?php
    include_once '../../include/footer.php';
} else {
    header('location: ' . BASEDIR . 'login');
}
