<?php
include_once '../../include/config.php';
if ($userData->isLoggedIn()) {
    header('location: '.BASEDIR.'index.php');
} else {
    include_once '../../include/header.php';
    ?>

    <div class="container">
        <form class="form-signup" method="POST" action="<?php echo BASEDIR; ?>pages/register.php" role="form">
            <h2 class="form-heading">Registrieren</h2>
            <input name="user_firstname" class="first form-control" placeholder="Vorname" type="text" required autofocus>
            <input name="user_name" class="form-control" placeholder="Nachname" type="text" required autofocus>
            <input name="user_email" class="form-control" placeholder="Email" type="email" required autofocus>
            <input name="user_password" class="form-control" type="password" placeholder="Password" required>
            <input name="user_password_repeat" class="last form-control" type="password" placeholder="Password wdhl." required>

            <br />
            
            <button class="btn btn-lg btn-primary btn-block" type="submit">registrieren</button>
        </form>

    </div> <!-- /container -->

    <?php
    include_once '../../include/footer.php';
}
