<?php
include_once '../../include/config.php';
if ($userData->isLoggedIn()) {
    header('location: ' . BASEDIR);
} else {
    include_once '../../include/header.php';
    ?>
    <div class="form-outer">
        <div class="form-inner">
            <div class="container">
                <form class="form-signin" method="POST" action="<?php echo BASEDIR; ?>checkLogin" role="form">
                    <?php
                    if (isset($_GET['code'])) {
                        if ($userData->checkCreateUserCode($_GET['code'])) {
                            ?>
                            <div class="alert alert-success">Ihr Account wurde freigeschaltet. Sie könne sich nun einloggen</div>
                            <?php
                        } else {
                            ?>
                            <div class="alert alert-danger">Der Freischaltcode ist leider falsch.</div>
                            <?php
                        }
                    }
                    ?>
                    <h2 class="form-heading"><img alt="logo" src="./img/logo.gif">Login</h2>
                    <input name="user_email" class="first form-control" placeholder="Email" type="email" required autofocus>
                    <input name="user_password" class="last form-control" type="password" placeholder="Password" required>

                    <br/>

                    <button class="loginButton btn btn-lg btn-primary btn-block" id="loginbutton" type="submit">Einloggen</button>
                    <a class="btn btn-lg btn-link btn-block" id="register" href="<?php echo BASEDIR; ?>register">Registrieren</a>
                    <a class="btn btn-lg btn-link btn-block" id="pwforgot" href="<?php echo BASEDIR; ?>forgotPassword">Passwort vergessen?</a>
                </form>
            </div> <!-- /container -->
        </div>
    </div>

    <?php
    include_once '../../include/footer.php';
}
