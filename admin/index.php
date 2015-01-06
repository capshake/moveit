<?php
include_once '../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    $headerTitle = 'Adminpanel';

    include_once '../include/header.php';
    include_once 'include/menu.php';
    ?>


    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h1>MoveIT</h1>
                <p class="lead">Adminpanel.</p>
            </div>
        </div>

    </div><!-- /.container -->

    <?php
    include_once '../include/footer.php';
} else {
    header('location: ' . BASEDIR . 'login');
}