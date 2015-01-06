<?php
include_once '../../include/config.php';


if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'pages/main/login.php');
} else {
    include_once '../../include/header.php';
    include_once '../../include/menu.php';
    ?>

    <div class="container">

        <div class="row">
            <div class="col-md-offset-4 col-md-4">
                <h1>Einstellungen</h1>


                <form method="POST" action="<?php echo BASEDIR; ?>settings" role="form">

                    <?php
                    if (isset($_POST['edit'])) {
                        $user_firstname = filter_var($_POST['user_firstname'], FILTER_SANITIZE_STRING);
                        $user_lastname = filter_var($_POST['user_lastname'], FILTER_SANITIZE_STRING);
                        $user_name = filter_var($_POST['user_name'], FILTER_SANITIZE_STRING);
                        $user_email = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);

                        $update = json_decode($userData->updateUser($_POST));
                        if ($update->status == 'error') {
                            ?>
                            <div class="alert alert-danger"><?php echo $update->msg; ?></div>
                            <?php
                        }
                        if ($update->status == 'success') {
                            ?>
                            <div class="alert alert-success"><?php echo $update->msg; ?></div>
                            <?php
                        }
                    }

                    $db->bind("id", $userData->getUserId());
                    $user = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_id = :id");
                    ?>


                    <div class="form-group">
                        <label for="user_name">Benutzername</label>
                        <input id="user_name" name="user_name" value="<?php echo $user['user_name']; ?>" class="first form-control" placeholder="Benutzername" type="text" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="user_firstname">Vorname</label>
                        <input id="user_firstname" name="user_firstname" value="<?php echo $user['user_firstname']; ?>" class="first form-control" placeholder="Vorname" type="text" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="user_lastname">Nachname</label>
                        <input id="user_lastname" name="user_lastname" value="<?php echo $user['user_lastname']; ?>" class="first form-control" placeholder="Nachname" type="text" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="user_email">Email</label>
                        <input id="user_email" name="user_email" value="<?php echo $user['user_email']; ?>" class="first form-control" placeholder="Email" type="text" required autofocus>
                    </div>

                    <button class="btn btn-lg btn-primary btn-block" type="submit" name="edit">speichern</button>
                </form>
            </div>
        </div>

    </div><!-- /.container -->

    <?php
    include_once '../../include/footer.php';
}
