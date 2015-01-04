<?php
include_once 'include/config.php';


if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'pages/main/login.php');
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
            if(isset($_POST['hi']) && $csrfToken->isValidToken(@$_POST['token'])) {
                
                echo $_POST['hi'];
                
                
                $csrfToken->newToken();
            }
            ?>
            <form method="POST" action="<?php echo BASEDIR.'index.php'; ?>">
                <input name="hi" value="ok">
                <input name="submit" type="submit" />
            </form>
        </div>

    </div><!-- /.container -->

    <?php
    include_once 'include/footer.php';
}



/* include_once 'include/config.php';


  $users = $db->query('SELECT * FROM users');


  echo json_encode($users);



  if($userData->isloggedIn()) {
  echo 'Hi, '.$userData->getFirstName() . ' '.$userData->getLastName().' ('.$userData->getUserName().')';
  echo '<br /><a href="'.BASEDIR.'include/logout.php">logout</a>';
  } else {
  echo 'nicht eingeloggt';
  echo '<br /><a href="'.BASEDIR.'pages/main/login.php">login</a>';
  } */

