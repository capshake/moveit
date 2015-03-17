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
                <form class="form-forgot-password" method="POST" action="<?php echo BASEDIR; ?>forgotPassword" role="form">
                    <?php
                    if (isset($_POST['resetPassword'])) {
                        $user_email = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);

                        $forgotPassword = json_decode($userData->resetUserPassword($user_email));
                        if ($forgotPassword->status == 'error') {
                            ?>
                            <div class="alert alert-danger"><?php echo $forgotPassword->msg; ?></div>
                            <?php
                        }
                        if ($forgotPassword->status == 'success') {
                            ?>
                            <div class="alert alert-success"><?php echo $forgotPassword->msg; ?></div>
                            <?php
                        }
                    }
                    ?>
                    <h2 class="form-heading">Passwort vergessen?</h2>
                    <input name="user_email" class="alone form-control" placeholder="Email" type="email" required autofocus>

                    <br />

                    <button class="btn btn-lg btn-primary btn-block" type="submit" name="resetPassword">Zusenden</button>
                    <a class="btn btn-lg btn-link btn-block" href="<?php echo BASEDIR; ?>login">zurück</a>
                </form>
            </div> <!-- /container -->
        </div>
    </div>

    <?php
    include_once '../../include/footer.php';
}
