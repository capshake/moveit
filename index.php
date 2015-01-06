<?php
include_once 'include/config.php';


if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'login');
} else {

    $headerTitle = 'dasdsad';


    include_once 'include/header.php';



    include_once 'include/menu.php';
    ?>


    <div class="container">

        <div class="starter-template">
            <h1>MoveIT</h1>
            <p class="lead">Startseite von unserem kleinen Projekt.</p>

            <?php
            if (isset($_POST['hi']) && $userData->isValidToken(@$_POST['token'])) {

                echo $_POST['hi'];


                $userData->newToken();
            }
            ?>
            <form method="POST" action="<?php echo BASEDIR . 'index.php'; ?>">
                <input name="hi" value="ok">
                <input name="submit" type="submit" />
            </form>
        </div>

    </div><!-- /.container -->

    <?php
    include_once 'include/footer.php';
}