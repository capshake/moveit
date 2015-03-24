<?php
include_once 'include/config.php';


if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'login');
} else {

    include_once 'include/header.php';

    include_once 'include/menu.php';
    ?>


    <div class="container">
       <div id="MapsLagerListen">
        <div class="col-xs-2">
            <div id="Altbau">
            <div id="Ueberschriften">
                <h4>Altbau</h4>
            </div>
            <!--Dropdown Menue fuer den Altbau-->
            <div id="AltbauAuswahl">
                <fieldset>
                    <div id="AltTrakt">
                        <select name="AltbauTrakt"  id="AltbauTrakt">
                            <option value="">Trakt</option>
                            <?php
                                $buildingsOld = $db -> query("SELECT building_id, building_name FROM " . TABLE_BUILDINGS . " WHERE building_type = 1");
                                foreach($buildingsOld as $buildingOld){
                                    echo "<option value = " . $buildingOld['building_id'] . ">" . $buildingOld['building_name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div id="AltEtage">
                        <select name="AltbauEtage" id="AltbauEtage">
                            <option value="">Vorher Trakt w&auml;hlen...</option>
                        </select>
                    </div>
                    <div id="AltRaum">
                        <select name="AltbauRaum" id="AltbauRaum">
                            <option value="">Vorher Etage w&auml;hlen...</option>
                        </select>
                    </div>
                </fieldset>
            </div>

            <!--AltbauListe-->
            <div id="altlist_wrapper">
                <h5 class="altlist"><b>Möbel in diesem Raum:</b></h5>
                <div id="AltbauListe" data-toggle="tooltip" data-placement="top" title="Ziehen Sie die Items in den Raum."></div>
            </div>
        </div>
    </div>
        <div class="col-xs-8">
           <div id="Neubau">
            <!--Dropdown Menue fuer den Neubau-->
            <div id="Ueberschriften">
             <h4>Neubau</h4>
             <button type="button" id="GrundrissNeubau" data-toggle="tooltip" data-placement="top" title="Hier können Sie ihren Raum im Gebäude finden.">MAP</button>
             <button type="button" id="Zollstock" data-toggle="tooltip" data-placement="top" title="Klicken Sie für den Zollstock.">Zollstock anzeigen</button>
             <label id="abstand-inline-label" class="abstand-anzeige" for="abstand-inputfield">Abstand in cm :</label>
			 <input id="abstand-inline-inputfield" class="abstand-anzeige" name="abstand-inputfield" ></input>
         </div>
             <div id="dialog-GrundrissNeubau" title="Neubau-Grundriss">

                <div id= "MapText">
                    <p>Hier finden Sie eine Übersicht über den Neubau.
                    Sie haben die Möglichkeit, Räume über eine Karte der jeweiligen Etage eines Gebäudes auszuwählen.</p>
                </div>
                <div id= "NeubauAuswahlMap">
                     <fieldset>
                        <div id = "NeuTraktMap">
                            <select name="NeubauTraktMap" id="NeubauTraktMap">
                                <option value="">Gebäude</option>
                                <?php
                                    $buildingsNewMap = $db -> query("SELECT building_id, building_name FROM " . TABLE_BUILDINGS . " WHERE building_type = 2");
                                    foreach($buildingsNewMap as $buildingNewMap){
                                        echo "<option value = " . $buildingNewMap['building_id'] . ">" . $buildingNewMap['building_name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div id = "NeuEtageMap">
                            <select name="NeubauEtageMap" id="NeubauEtageMap">
                                <option value="">Vorher Gebäude wählen...</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div id="Map"></div>


             </div>

    <!-- Dropdown-Menü für den Neubau -->
         <div id="NeubauAuswahl">
            <fieldset>
                <div id="NeuTrakt">
                    <select name="NeubauTrakt" id="NeubauTrakt">
                        <option value="">Trakt wählen</option>
                        <?php
                            $buildingsNew = $db -> query("SELECT building_id, building_name FROM " . TABLE_BUILDINGS . " WHERE building_type = 2");
                            foreach($buildingsNew as $buildingNew){
                                echo "<option value = " . $buildingNew['building_id'] . ">" . $buildingNew['building_name'] . "</option>";
                            }
                        ?>
                    </select>
                </div>
                <div id="NeuEtage">
                    <select name="NeubauEtage" id="NeubauEtage">
                        <option value="">Vorher Trakt wählen...</option>
                    </select>
                </div>
                <div id="NeuRaum">
                    <select name="NeubauRaum" id="NeubauRaum">
                        <option value="">Vorher Etage wählen...</option>
                    </select>
                </div>
            </fieldset>

        </div>
    <!-- Ende Dropdown-Menü für Neubau -->

        <!--NeubauMap-->
        <div>
            <div id="NeubauMap" style="overflow: visible;"></div>
        </div>

    </div>
           </div>


    <!--Lager-->

    <div class="col-xs-2">
        <div id="LagerLeiste">
            <div id="ObereLeiste">
                <!-- Tooltips und Tabs -->
                <ul>
                    <li><a href="#Lager" data-toggle="tooltip" data-placement="top" title="Verschieben Sie Möbelstücke per Drag&Drop in Ihr persönliches Lager, um sie zu einem späteren Zeitpunkt einem neuen Raum zuzuweisen" id="LagerTab">Lager</a></li>
                    <li><a href="#Müll" data-toggle="tooltip" data-placement="top" title="Verschieben Sie Möbelstücke per Drag&Drop in den Müll, um sie zur Aussortierung zu markieren" id="MüllTab">Müll</a></li>
                </ul>

                <!-- Tab Lager -->
                <div id= "Lager">
                    <div id="LagerListe"></div>
                </div>
                <!-- Tab Müll -->
                <div id= "Müll">
                    <div id="MuellListe"></div>
                </div>
            </div>

            <!-- Öffentliches Lager -->
            <div id="oeffentlichesLager">
                <div id="oeffentlichesLagerUeberschrift">
                    <label>Öffentliches Lager</label>
                    <button type="button" class="btn btn-default btn-lg" id="btnOeffReset"><span class="glyphicon glyphicon-repeat"></span></button>
                </div>

                <div id="oeffentlichesLagerListe"></div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    //Funktion beim Verlassen der Seite/Aktualisieren der Seite
    window.onbeforeunload = function () {
        return "Sie verlassen die Seite ohne zu Speichern.";
    };
    </script>
    </div><!-- /.container -->

    <?php
    include_once 'include/footer.php';
}
