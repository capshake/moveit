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
                <h2>Impressum</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="well">
                    <h5>Fachhochschule Düsseldorf - University of Applied Science</h5>
                    <p>Universitätsstraße Geb. 23.31/32
                        <br> D 40225 Düsseldorf
                        <br> Telefon: +49 211 4351 0
                        <br> Telefax: +49 211 811 4916
                        <br> E-Mail: 404 </p>

                    <p>Die Fachhochschule Düsseldorf ist eine Körperschaft des Öffentlichen Rechtes. Sie wird durch die Präsidentin, Prof.Dr.Brigitte Grass, gesetzlich vertreten.</p>

                    <h5>Zuständige Aufsichtsbehörde:</h5>
                    <p>Ministerium für Wissenschaft und Forschung des Landes Nordrhein-Westfalen
                        <br> Völklinger Straße 49
                        <br> D 40221 Düsseldorf
                        <br> Telefon: +49 211 896 04
                        <br> Telefax: +49 211 896 4555
                        <br> </p>

                    <p>Umsatzsteuer-Identifikationsnummer gemäß §27a Umsatzsteuergesetz: DE-119432315
                    </p>

                    <h5>Haftungshinweis:</h5>
                    <p>Die FH Düsseldorf hat keinen Einfluss auf Gestaltung und Inhalte fremder Internetseiten.
                        <br> Für die Inhalte von Internetseiten, auf die externe Links verweisen, übernimmt die FH Düsseldorf deshalb keine Verantwortung.</p>

                    <h5>Redaktion, Gestaltung und technologische Betreuung:</h5>
                    <p>Für die Betreuung des Internetangebotes der Fachbereiche und Einrichtungen der FH Düsseldorf
                        <br> liegt die Verantwortung in den jeweiligen Fachbereichen und Einrichtungen. </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="well">
                    <h4>Datenschutzerklärung</h4>
                    <br>
                    <br>
                    <h4>Sinn und Zweck des Web-Angebots moveIT</h4>
                    <p>Das Web-Angebot moveIT soll Ihnen bei der Planung des Umzugs in das neue Fachhochschulgebäude am Campus Derendorf helfen. Die Möbel aus Ihren derzeitigen Büroräumen können virtuell in das neue Hochschulgebäude geräumt werden. So ist es Ihnen möglich 1. einen Überblick über Ihr Inventar in den neuen Räumen zu bekommen sowie 2. eine Auswahl zu treffen, welche Möbel auf den neuen Campus umziehen sollen.</p>
                    <br>
                    <h4>Personenbezogene Daten</h4>
                    <p>Alle personenbezogenen Daten Ihres Accounts geben Sie selber ein. Wichtig für die Organisation sind für den Fachbereich Ihr Name und eine E-Mail-Adresse, unter der Sie erreichbar sind.
                    <p>

                    <p>Daten und Informationen, die Sie in moveIT eingeben, sind zu Ihrer eigenen Information und Organisation bestimmt. Sie werden nicht von uns kontrolliert, Dritte können diese nicht einsehen.</p>
                    <br>
                    <h4>Löschen Ihrer Daten</h4>
                    <p>Falls Sie das Angebot des moveIT-Umzugsplaners nicht länger nutzen wollen und darüber hinaus wünschen, dass Ihre Daten gelöscht werden, wenden Sie sich mit diesem Wunsch an den Admin (<a href="mailto:admin@moveit.de">admin@moveit.de</a>).</p>
                </div>
            </div>
        </div>
    </div>

    <?php
    include_once '../../include/footer.php';
}