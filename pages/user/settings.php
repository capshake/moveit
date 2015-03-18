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
        <div class="col-md-offset-2 col-md-4">
            <h2>Benutzereinstellungen</h2>
        </div>
    </div>

        <form method="POST" action="<?php echo BASEDIR; ?>settings" role="form">

            <?php if (isset($_POST[ 'edit'])) { $update=json_decode($userData->updateUser($_POST)); if ($update->status == 'error') { ?>
                        <div class="row">
                <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-danger">
                <?php echo $update->msg; ?></div>
                            </div>
            </div>
            <?php } if ($update->status == 'success') { ?>
                    <div class="row">
                <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-success">
                <?php echo $update->msg; ?></div>
                        </div>
            </div>
            <?php } } $db->bind("id", $userData->getUserId()); $user = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_id = :id"); ?>
            
            
            <div class="row">
                <div class="col-md-4 col-md-offset-2">
                    <div class="well">

                        <div class="form-group">
                            <label for="user_firstname">Vorname</label>
                            <input id="user_firstname" name="user_firstname" value="<?php echo $user['user_firstname']; ?>" class="first form-control" placeholder="Vorname" type="text" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_lastname">Nachname</label>
                            <input id="user_lastname" name="user_lastname" value="<?php echo $user['user_lastname']; ?>" class="first form-control" placeholder="Nachname" type="text" required autofocus>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="well">
                        <div class="form-group">
                            <label for="user_lastname">Altes Passwort</label>
                            <input id="user_lastname" name="user_old_password" value="Altes Passwort" class="first form-control" placeholder="Nachname" type="text" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_lastname">Altes Passwort wiederholen</label>
                            <input id="user_lastname" name="user_repeat_old_password" value="Altes Passwort wiederholen" class="first form-control" placeholder="Nachname" type="text" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_lastname">Neues Passwort</label>
                            <input id="user_lastname" name="user_new_password" value="Neues Passwort" class="first form-control" placeholder="Nachname" type="text" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_email">Email</label>
                            <input id="user_email" name="user_email" value="<?php echo $user['user_email']; ?>" class="first form-control" placeholder="Email" type="email" required autofocus>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <button class="btn btn-primary btn-block col-md1-12" type="submit" name="edit" id="save_settings">Speichern</button>
                </div>
            </div>
        </form>
    </div>

    <?php
    include_once '../../include/footer.php';
}
