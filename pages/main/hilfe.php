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
            <div class="col-md-12">
                <h2>Hilfe</h2>
            </div>
        </div>
        <div class="row">

            <div class=" col-md-12">
                <div class="well">
                    <!-- Start des Akkordeons -->
                    <div id="akkordeon">
                        <h3>  Wie setzt sich die Hauptseite zusammen? </h3> <!-- Erster faltbarer Teil -->
                        <div>
                            <p>
                                Die Hauptseite des moveIT-Umzugplaners setzt sich aus den folgenden 3 Bereichen (von rechts nach links) zusammen:
                            <ul>
                                <li>Altbau</li>
                                <li>Raumbearbeitung im Zentrum</li>
                                <li>Lagerleiste mit (von oben nach unten) persönlichem Lager, Liste der aussortierten Möbel und öffentlichem Lager</li>
                            </ul>
                            Welche Funktionen im Detail in den einzelnen Bereichen angeboten werden, entnehmen Sie den folgenden Abschnitten.
                            </p>
                            <br>
                            <img class="helpScreenshot" src="./img/Screenshot1.png"> 
                        </div>
                        <br>
                        <br>
                        <h3> Welche Funktion hat der Altbaubereich? </h3> <!-- Zweiter faltbarer Teil -->
                        <div>
                            <p>
                                Im Altbaubereich werden Ihnen genau zwei Funktionen angeboten:
                            <ul>
                                <li><strong>Raum auswählen:</strong>Über drei Dropdown-Menüs wählen Sie einen Altbauraum aus, für den Sie Bearbeitungsrechte haben. Beachten Sie dabei bitte, dass
                                    das Dropdown-Menü von oben nach unten ausgefüllt werden muss. Sobald ein Raum ausgewählt wurde erscheint darunter das Inventar des Raums (siehe unten).</li>
                                <li><strong>Inventar des gewählten Raums verwalten:</strong>Sofern Sie berechtigt sind den ausgewählten Raum zu bearbeiten, erscheint unterhalb des Dropdown-Menüs eine Liste
                                    mit dem Inventar des Raums. Items der Liste können per Drag and Drop aus der Liste in den Raumbearbeitungsbereich gezogen werden. Items die einmal aus dem Altbau 
                                    genommen wurden können nicht mehr in den Altbau zurückgeschoben werden - falls diese Items aus dem aktuellen Raum genommen werden sollen, ziehen Sie das entsprechende
                                    Möbelstück in eines der Lager auf der rechten Seite. (z.B. persönliches Lager).</li>
                            </ul>
                            </p>

                            <br>
                                <img class="helpScreenshot" src="./img/Screenshot2_altbau.png">                        
                        </div>
                        <br>
                        <br>
                        <h3> Welche Funktion hat der Neubaubereich? </h3> <!-- Dritter faltbarer Teil -->
                        <div>
                            <p>
                                Im Neubaubereich findet die direkte Raumbearbeitung, d.h. das Positionieren der Möbel, statt. Folgende Funktionen sind gegeben:
                            <ul>
                                <li><strong>Auswahl des Raumes:</strong> Über die Dropdown-Menüs kann ein Raum des Neubaus ausgewählt werden. 
                                    Der ausgewählte Raum öffnet sich dann im mittleren Bereich und steht zur Bearbeitung zur Verfügung. </li>
                                <li><strong>Der MAP-Button:</strong>Durch Klicken auf den MAP-Button öffnet sich ein Dialog, in dem der Grundriss
                                    des Neubaus angezeigt wird. Das dient u.a. zur Ortung eines Raumes. Alternativ kann über die Karte ein Raum zur 
                                    Bearbeitung ausgewählt werden - hierzu muss der entsprechende Raum angeklickt werden.
                                </li>   
                                <li><strong>Der Zollstock-Button:</strong> Durch Klicken auf den Zollstock-Button erscheinen im Raumbearbeitungsbereich zwei Winkel,
                                    die innerhalb des Raumes positioniert werden können. Rechts von den Dropdown-Menüs wird Ihnen der Abstand zwischen den zwei Punkten
                                    in cm angezeigt. Wenn Sie nicht möchten, dass die Winkel weiterhin angezeigt werden, klicken Sie links neben der Anzeige des Abstands auf
                                    <em>Zollstock verstecken</em>.
                                </li>
                            </ul>
                            </p>
                            <br>
                            <img class="helpScreenshot" src="./img/Screenshot3_neubau.png"> 
                        </div>
                        <br>
                        <br>
                        <h3>Welche Funktionen haben die Lager?</h3> <!-- Vierter faltbarer Teil -->
                        <div>
                            <p>
                            <ul>
                                <li><strong>Persönliches Lager:</strong>Falls Sie zunächst keine Verwendung für ein oder mehrere Möbelstücke finden,
                                    können Sie diese mittels Drag-and-Drop in das (persönliche) Lager verschieben.</li>
                                <li><strong>Aussortierte Möbel:</strong> Möbelstücke, die defekt oder nicht mehr brauchbar sind, verschieben Sie per Drag-and-Drop in die Liste der aussortierten Möbel.</li>
                                <li><strong>Öffentliches Lager:</strong> Hier können Möbel abgelegt werden, die noch funktionstüchtig sind, für die Sie aber keine Verwendung mehr haben. Andere Nutzer
                                    können diese Items auch verwenden.</li>
                            </ul>
                            </p>
                            <br>
                            <img class="helpScreenshot" src="./img/Screenshot4_lager.png"> 
                        </div>
                        <br>
                        <br>
                        <h3>Wie positioniere/rotiere ich Möbel?</h3> <!-- Fünfter faltbarer Teil -->
                        <div>
                            <p>Möbel werden in moveIT per Drag-and-Drop aus den verschiedenen Lagerlisten (Liste des äquivalenten Altbauraums, persönliches und öffentliches Lager) auf die Raumbearbeitungsfläche
                                (im Zentrum der Seite) gezogen. Sobald ein Listeneintrag in den Raum "gedropped" wurde erscheint anstelle vom Listeneintrag ein Icon, dass das jeweilige Möbelstück repräsentiert. 
                                Das Item kann nach Belieben im Raum platziert werden. Dieses Möbelstück können Sie mittels <em>Doppelklick</em> um 90° im Uhrzeigersinn rotieren.
                            </p>

                        </div>
                        <br>
                        <br>
                        <h3>Wie kann ich für Räume freigeschaltet werden?</h3> <!-- Sechster faltbarer Teil -->
                        <div>
                            <p>Die Freischaltung der von Ihnen auswählbaren Räume erfolgt durch den Admin. Falls es Probleme mit der Freischaltung gibt oder Räume fehlen, wenden Sie sich bitte an den Admin.</p>
                        </div>
                        <br>
                        <br>
                        <h3>Können Möbel in mehreren Räumen verwendet werden?</h3> <!-- Siebter faltbarer Teil -->
                        <div>
                            <p>Ein Möbelstück kann immer nur in einem Raum positioniert werden, d.h. dasselbe Item kann nicht in mehreren Räumen gleichzeitig sein. Natürlich ist es aber möglich ein Item in einen
                                anderen Raum zu verschieben. Hierzu muss das Item aus dem Raum per Drag-and-Drop in das persönliche Lager verschoben werden. Danach kann ein anderer Neubauraum gewählt werden und das
                                Möbelstück kann im neuen Raum verwendet werden.</p>
                        </div>
                    </div>
                    <br>
                    <br> Falls Sie noch <em>weitere Fragen</em> zur Verwendung von moveIT haben, schreiben sie dem Admin eine E-Mail: <a href="mailto: moveit-admin@fh-duesseldorf.de">moveit-admin@fh-duesseldorf.de</a>
                </div>
            </div>
        </div>

    </div>

    
    <?php
    include_once '../../include/footer.php';
}