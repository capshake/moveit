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
                <p>	
                <ul>
                <li> <b>Benutzer:</b>  Im Benutzer Bereich ist es möglich neue Benutzer zu erstellen und ihnen Rollen und Räume zuzuweisen. Außerdem kann man Nutzer "manuell" freischalten, falls die Freischaltung mit dem automatisch versendeten Links nicht funktioniert hat.</li> <br>

					<li><b>Räume:</b> Im Räume Bereich ist es sowohl möglich Räume hinzuzufügen als auch zu löschen.</li> <br>

					<li><b>Map editor:</b> Im Map editor Bereich muss man die Maps hochladen die man verwenden möchte und auswählen wie der Trakt heißt. Außerdem kann man noch aussuchen welche Etage dargestellt wird. </li> <br>
					<li><b>Gebäude:</b> Im Gebäude Bereich werden Trakte eingetragen und es wird ausgewählt ob dieser Trakt zum Alt oder Neubau gehört. </li> <br>
					<li><b>Import/Export:</b>Im Import Bereich kann eine .csv-Datei, mit den Möbeln die eingepflegt werden sollen, hochgeladen werden und auch exportiert werden. Außerdem kann die Datenbank zurückgesetzt werden, dies hat zur Folge, dass die im
					Vorfeld importierten Daten gelöscht werden.</li> <br> 
				    </p>
				</ul>

            </div>
        </div>

    </div><!-- /.container -->

    <?php
    include_once '../include/footer.php';
} else {
    header('location: ' . BASEDIR . 'login');
}
