<?php
include_once '../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    $headerTitle = 'moveIT Adminpanel';

    include_once '../include/header.php';
    include_once 'include/menu.php';
    ?>


    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h1><?php echo $headerTitle?> Adminpanel</h1>
                <p class="lead">Bitte eine der Optionen aus der Navigationsleiste auswählen!</p>
            </div>
        </div>

    </div><!-- /.container -->

    <?php
    include_once '../include/footer.php';
} else {
    header('location: ' . BASEDIR . 'login');
}
