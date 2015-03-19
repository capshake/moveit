<?php
include_once '../../include/config.php';


if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'login');
} else {

    include_once '../../include/header.php';

    include_once '../../include/menu.php';
    ?>
  		  
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-2">
            <h2>Hilfe</h2>
        </div>
    </div>
    <div class="row">

        <div class=" col-md-8 col-md-offset-2">
            <div class="well">
                <div id="akkordeon">
                    <h3> +  Wie setzt sich die Seite zusammen? </h3>
                    <div>
                        <p>
                            Die Hauptseite des moveIT-Umzugplaners setzt sich aus den folgenden 3 Bereichen (von rechts nach links) zusammen:
                            <ul>
                                <li>Altbau</li>
                                <li>Raumbearbeitung im Zentrum</li>
                                <li>Lagerleiste mit 1. persönlichem Lager/Wunschliste/Müll und 2. öffentlichem Lager</li>
                            </ul>
                            Welche Funktionen im Detail in den einzelnen Bereichen angeboten werden, entnehmen Sie den folgenden Abschnitten.
                        </p>
                        <br>
                        <a href="#" data-toggle="modal" data-target="#lightbox">
                            <img class="anleitung" src="./img/1Anleitung.png">
                        </a>
                    </div>
                    <br>
                    <br>
                    <h3>+  Welche Funktion hat der Altbaubereich? </h3>
                    <div>
                        <p>
                            Im Altbaubereich werden Ihnen genau zwei Funktionen angeboten:
                            <ul>
                                <li><strong>Raum auswählen:</strong>Im rot-unterlegten oberen Teil können Sie einen Raum des Altbaus über drei Drop-Down-Meünus wählen.</li>
                                <li><strong>Inventar des gewählten Raums verwalten:</strong>Sofern Sie berechtigt sind den ausgewählten Raum zu bearbeiten, erscheint unterhalb der Drop-Down-Menüs eine Liste
                                    mit dem Inventar des Raums.</li>
                            </ul>
                        </p>
                        
                        <br>
                        <a href="#" data-toggle="modal" data-target="#lightbox">
                            <img class="anleitung" src="./img/2Anleitung.png">
                        </a>

                    </div>
                    <br>
                    <br>
                    <h3>+  Was ist die Zollstock-Funktion? </h3>


                    <div>
                        Die <b>Zollstock</b>-Funktion dient zur Abmessung von Abständen zwischen Möbelstücken. Dafür wählen Sie die Zollstock-Funktion aus, klicken dann einmal, um den Startpunkt festzulegen, und ein zweites mal, um den Endpunkt auszuwählen.
                        <br>
                        <a href="#" data-toggle="modal" data-target="#lightbox">
                            <img class="anleitung" src="./img/3Anleitung.png">
                        </a>
                    </div>
                    <br>
                    <br>
                    <h3>+  Wofür dienen die verschiedenen Lager? </h3>
                    <div>
                        Falls Sie zunächst keine Verwendung für ein oder mehrere Möbelstücke finden, können Sie diese ebenfalls mittels drag-and-drop in das (persönliche) <b>Lager</b> verschieben. Möbelstücke, die defekt oder nicht mehr brauchbar sind, verschieben Sie in den <b>"Müll"</b>. Das <b>öffentliche Lager</b> dient der Möbelablage, die noch funktionstüchtig sind, für Sie aber keine weitere Verwendung haben.
                        <a href="#" data-toggle="modal" data-target="#lightbox">
                            <img class="anleitung" src="./img/4Anleitung.png">
                        </a>
                    </div>
                </div>
                <br>
                <br> Falls Sie noch weitere Fragen, über die Verwendung unserer Website haben, schreiben sie unserem Admin eine E-Mail: <a href="mailto: admin@moveit.de">admin@moveit.de</a>
            </div>
        </div>
    </div>

</div>

<div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
        <div class="modal-content">
            <div class="modal-body">
                <img src="" alt="" />
            </div>
        </div>
    </div>
</div>
<?php
    include_once '../../include/footer.php';
}