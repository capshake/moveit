<?php
include_once '../../include/config.php';


if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'pages/main/login.php');
} else {
    include_once '../../include/header.php';
    include_once '../../include/menu.php';

    $db->bind("id", $userData->getUserId());
    $user = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_id = :id");
    ?>

    <div class="container">
        <div class="starter-template">
            <h1>Einstellungen</h1>
        </div>
    </div><!-- /.container -->

    <?php
    include_once '../../include/footer.php';
}
