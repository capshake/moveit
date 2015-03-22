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
                <h2>moveIT Adminpanel</h2>
                <p>Herzlich Willkommen im moveIT Adminpanel! Von hier aus lassen sich die Nutzer und Umzugsdaten verwalten. Folgende Funktionen stehen dazu zur Verfügung:</p>

                <br>

                <h4><a href="<?php echo BASEDIR; ?>admin/ImportExport">Import/Export</a></h4>
                <p>Es können vorhandene Möbellisten des Spediteurs als CSV-Datei importiert werden und nach Fertigstellung der Umzugsplanung exportiert werden. Außerdem befindet sich hier eine Möglichkeit um alle Umzugsdaten zu zurückzusetzen.
                <b>Dies betrifft alle Gebäude, Räume, Möbelstücke und Nutzer-Bearbeitungsrechte. Nutzerkonten bleiben jedoch erhalten!</b></p>

                <h4><a href="<?php echo BASEDIR; ?>admin/buildings">Gebäudeverwaltung</a></h4>
                <p>Gebäude bzw. Trakte werden hier erstellt und verwaltet. Es wird zusätzlich beschrieben, wo sich das Gebäude befindet. So ist der H-Trakt ein Teil des Bestandes (Altbau) in Golzheim und Gebäude 4 gehört zum Neubau in Derendorf.</p>

                <h4><a href="<?php echo BASEDIR; ?>admin/mapEditor">Map-Editor</a></h4>
                <p>Maps sind Kombinationen von Gebäuden und ihren Etagen. Hier wird bspw. für jede Etage des H-Traktes eine Map angelegt, also "H-Trakt, EG", "H-Trakt, 1.OG", usw. Für Etagen des Neubaus lassen sich zusätzlich Karten hochladen.
                Für diese Karten lässt sich ein Maßstab festlegen und Räume der Etage platzieren. <b>Auf diese Weise wird die Größe der Räume für die Möbelplanung der Nutzer bestimmt!</b></p>

                <h4><a href="<?php echo BASEDIR; ?>admin/rooms">Raumverwaltung</a></h4>
                <p>Räume können erstellt und umbenannt werden. Bei der Erstellung wird jeweils der Standort eines Raumes festgelegt, z.B. befindet sich Raum H1.11 im H-Trakt, 1.OG. Der genaue Standort sowie die Größe des Raumes können im Map-Editor
                zum "H-Trakt, 1.OG" angepasst werden. <b>Bitte beachten: Die Map eines Raumes lassen sich nach Erstellung nicht ändern. Sollte das gewünscht sein, muss dieser Raum gelöscht und neu angelegt werden!</b></p>

                <h4><a href="<?php echo BASEDIR; ?>admin/users">Benutzerverwaltung</a></h4>
                <p>Bietet Funktionen zur Nutzerverwaltung wie Anlegen und Löschen von Nutzern. <b>Dort können Nutzern Räume zugewiesen werden, in denen sie über Bearbeitungsrechte verfügen.</b></p>
            </div>
        </div>

    </div><!-- /.container -->

    <?php
    include_once '../include/footer.php';
} else {
    header('location: ' . BASEDIR . 'login');
}
