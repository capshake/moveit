<?php
include_once '../../include/config.php';
if ($userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'index.php');
} else {
    include_once '../../include/header.php';
    ?>

    <div class="form-outer">
        <div class="form-inner">
            <div class="container">
                <form class="form-signup" method="POST" action="<?php echo BASEDIR; ?>register" role="form">

                    <?php
                    $user_firstname = '';
                    $user_lastname = '';
                    $user_email = '';

                    if (isset($_POST['register'])) {
                        $user_firstname = filter_var($_POST['user_firstname'], FILTER_SANITIZE_STRING);
                        $user_lastname = filter_var($_POST['user_lastname'], FILTER_SANITIZE_STRING);;
                        $user_email = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);

                        $register = json_decode($userData->createUser($_POST));
                        if ($register->status == 'error') {
                            ?>
                            <div class="alert alert-danger"><?php echo $register->msg; ?></div>
                            <?php
                        }
                        if ($register->status == 'success') {
                            ?>
                            <div class="alert alert-success"><?php echo $register->msg; ?></div>
                            <?php
                        }
                    }
                    ?>

                    <h2 class="form-heading">Registrieren</h2>
                    <input name="user_firstname" value="<?php echo $user_firstname; ?>" class="first form-control" placeholder="Vorname" type="text" required autofocus>
                    <input name="user_lastname" value="<?php echo $user_lastname; ?>" class="first form-control" placeholder="Nachname" type="text" required autofocus>
                    <input name="user_email" value="<?php echo $user_email; ?>" class="form-control" placeholder="Email" type="email" required autofocus>
                    <input name="user_password" class="form-control" type="password" placeholder="Password" required>
                    <input name="user_password_repeat" class="last form-control" type="password" placeholder="Password wdhl." required>

                    <br />

                    <button class="btn btn-lg btn-primary btn-block" type="submit" name="register">registrieren</button>
                    <a class="btn btn-lg btn-link btn-block" href="<?php echo BASEDIR; ?>login">zurück</a>
                </form>
            </div> <!-- /container -->
        </div>
    </div>

    <?php
    include_once '../../include/footer.php';
}
