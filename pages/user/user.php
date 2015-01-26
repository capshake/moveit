<?php
include_once '../../include/config.php';


if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'pages/main/login.php');
} else {
    include_once '../../include/header.php';
    include_once '../../include/menu.php';

    $db->bind("id", $_GET['id']);
    $user = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_id = :id");
    ?>

    <div class="container">
    <?php
    if ($user['user_id']) {
        ?>
            <h1><?php echo $user['user_name']; ?></h1>
            <p class="lead"><?php echo $user['user_firstname']; ?> <?php echo $user['user_lastname']; ?></p>
        <?php
    } else {
        ?>
            <h1>Fehler!</h1>
            <p class="lead">Der Benutzer wurde nicht gefunden.</p>
        <?php
    }
    ?>
    </div><!-- /.container -->

    <?php
    include_once '../../include/footer.php';
}