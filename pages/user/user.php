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
        <div class="row">
            <div class="col-md-offset-3 col-md-4">
                <h2>Profil</h2>
            </div>
        </div>
        <?php
        if ($user['user_id']) {
            ?>
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Benutzer</div>
                        <div class="panel-body">
                            <strong>Name: </strong><?php echo $user['user_firstname']; ?> <?php echo $user['user_lastname']; ?><br />
                            <strong>Email: </strong><?php echo $user['user_email']; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>

            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            Der Benutzer wurde nicht gefunden.
                        </div>
                    </div>
                </div>
            </div>


            <?php
        }
        ?>
    </div><!-- /.container -->

    <?php
    include_once '../../include/footer.php';
}