<?php
include_once '../../include/config.php';
if ($userData->isLoggedIn()) {
    header('location: '.BASEDIR.'index.php');
} else {
    include_once '../../include/header.php';
    ?>

    <div class="container">
        <form class="form-signin" method="POST" action="<?php echo BASEDIR; ?>include/checkLogin.php" role="form">
            <h2 class="form-heading">Loggen Sie sich ein</h2>
            <input name="user_email" class="first form-control" placeholder="Email" type="email" required autofocus>
            <input name="user_password" class="last form-control" type="password" placeholder="Password" required>

            <br />
            
            <button class="btn btn-lg btn-primary btn-block" type="submit">einloggen</button>
        </form>

    </div> <!-- /container -->

    <?php
    include_once '../../include/footer.php';
}
