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
                    <h3>  Wie setzt sich die Seite zusammen? </h3>
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
                    <h3> Welche Funktion hat der Altbaubereich? </h3>
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
                    <h3> Welche Funktion hat der Neubaubereich? </h3>


                    <div>
                        <p>
                            Im Neubaubereich findet die direkte Raumbearbeitung, d.h. Positionieren der Möbel, statt. Folgende Funktionen sind gegeben:
                            <ul>
                                <li><strong>Auswahl des Raumes:</strong> Über die Dropdown-Menüs kann ein Raum des Neubaus ausgewählt werden. 
                                    Der ausgewählte Raum öffnet sich dann im mittleren Bereich und steht zur Bearbeitung zur Verfügung. </li>
                                <li><strong>Der MAP-Button:</strong>Durch Klicken auf den MAP-Button öffnet sich ein Dialog, in dem der Grundriss
                                    des Neubaus angezeigt wird. Das dient u.a. zur Ortung eines Raumes. Alternativ kann über die Karte ein Raum zur 
                                    Bearbeitung ausgewählt werden - hierzu muss der entsprechende Raum angeklickt werden.
                                </li>   
                                <li><strong>Der Zollstock-Button:</strong> Durch Klicken auf den Zollstock-Button erscheinen im Raumbearbeitungsbereich zwei Winkel,
                                    die innerhalb des Raumes positioniert werden können. Rechts von den Dropdown-Menüs wird Ihnen der Abstand zwischen den zwei Punkten
                                    in cm angezeigt. 
                                </li>
                            </ul>
                        </p>
                        <br>
                        <a href="#" data-toggle="modal" data-target="#lightbox">
                            <img class="anleitung" src="./img/3Anleitung.png">
                        </a>
                    </div>
                    <br>
                    <br>
                    <h3>Welche Funktion haben die Lager?</h3>
                    <div>
                        <p>
                            <ul>
                                <li><strong>Persönliches Lager:</strong>Falls Sie zunächst keine Verwendung für ein oder mehrere Möbelstücke finden,
                                 können Sie diese ebenfalls mittels drag-and-drop in das (persönliche) Lager verschieben.</li>
                                <li><strong>Müll:</strong>Möbelstücke, die defekt oder nicht mehr brauchbar sind, verschieben Sie ebenfalls per drag-and-drop in den "Müll".</li>
                                <li><strong>Öffentliches Lager:</strong> Hier können Möbel abgelegt werden, die noch funktionstüchtig sind, für die Sie aber keine Verwendung mehr haben.</li>
                            </ul>
                        </p>
                        <br>
                        <a href="#" data-toggle="modal" data-target="#lightbox">
                            <img class="anleitung" src="./img/3Anleitung.png">
                        </a>
                    </div>
                    <br>
                    <br>
                    <h3> Wofür dienen die verschiedenen Lager? </h3>
                    <div>
                          
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