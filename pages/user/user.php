<?php
include_once '../../include/config.php';


if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'pages/main/login.php');
} else {
    include_once '../../include/header.php';
    include_once '../../include/menu.php';

    $db->bind("id", $_GET['id']);
    $user = $db->row("SELECT * FROM users WHERE user_id = :id");
    

    ?>

    <div class="container">
        <?php
        if ($user['user_id']) {
            ?>
            <div class="starter-template">
                <h1><?php echo $user['user_name']; ?></h1>
                <p class="lead"><?php echo $user['user_firstname']; ?> <?php echo $user['user_lastname']; ?></p>
            </div>
            <?php
        } else {
            ?>
            <div class="starter-template">
                <h1>Fehler!</h1>
                <p class="lead">Der Benutzer wurde nicht gefunden.</p>
            </div>
            <?php
        }
        ?>
    </div><!-- /.container -->

    <?php
    include_once '../../include/footer.php';
}



/* include_once 'include/config.php';


  $users = $db->row('SELECT * FROM users');


  echo json_encode($users);



  if($userData->isloggedIn()) {
  echo 'Hi, '.$userData->getFirstName() . ' '.$userData->getLastName().' ('.$userData->getUserName().')';
  echo '<br /><a href="'.BASEDIR.'include/logout.php">logout</a>';
  } else {
  echo 'nicht eingeloggt';
  echo '<br /><a href="'.BASEDIR.'pages/main/login.php">login</a>';
  } */

