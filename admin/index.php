<?php
include_once '../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    $headerTitle = 'moveIT Adminpanel';

    include_once '../include/header.php';
    include_once 'include/menu.php';
    ?>


    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h1><?php echo $headerTitle?> Adminpanel</h1>
                <h2 class="lead">Anleitung</h2>
                <p>	<b>Benutzer</b> <br> Im „Nutzer“ Bereich könnnen neue Benutzer angelegt werden. Es müssen einfach die geforderten Daten eingetragen werden und auf „Nutzer hinzufügen“ geklickt werden. <br>

					<br> <b>Räume</b> <br> Im Grundriss Bereich können Grundrisse zunächst hochgeladen und dann bearbeitet werden. Wenn man diese bearbeiten möchte, können einfach kästchen gezogen werden und somit Räume erstellt werden <br>

					<br> <b>Map editor</b> <br>  blabla  <br>
					<br> <b>Gebäude</b> <br>  blabla  <br> 
					<br> <b>Import/Export</b> <br>   blabla <br></p>


            </div>
        </div>

    </div><!-- /.container -->

    <?php
    include_once '../include/footer.php';
} else {
    header('location: ' . BASEDIR . 'login');
}
