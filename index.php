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

                <div class="panel panel-default">
                    <div class="panel-heading">Altbau</div>
                    <div class="panel-body">

                        <div id="Altbau">

                            <!--Dropdown Menue fuer den Altbau-->

                            <div id="AltTrakt">
                                <select class="form-control" name="AltbauTrakt"  id="AltbauTrakt">
                                    <option value="">Trakt</option>
                                    <?php
                                    $buildingsOld = $db->query("SELECT building_id, building_name FROM " . TABLE_BUILDINGS . " WHERE building_type = 1");
                                    foreach ($buildingsOld as $buildingOld) {
                                        echo "<option value = " . $buildingOld['building_id'] . ">" . $buildingOld['building_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div id="AltEtage">
                                <select class="form-control" name="AltbauEtage" id="AltbauEtage">
                                    <option value="">Vorher Trakt w&auml;hlen...</option>
                                </select>
                            </div>
                            <div id="AltRaum">
                                <select class="form-control" name="AltbauRaum" id="AltbauRaum">
                                    <option value="">Vorher Etage w&auml;hlen...</option>
                                </select>
                            </div>


                        </div>

                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">Möbel im Raum</div>
                    <div class="panel-body">
                        <div id="AltbauListe" data-toggle="tooltip" data-placement="top" title="Ziehen Sie die Items in den Raum."></div>
                    </div>
                </div>

            </div>
            <div class="col-xs-8">





                <div id="Neubau">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>Neubau</h4>
                            <button class="btn btn-success btn-sm" type="button" id="GrundrissNeubau" data-toggle="tooltip" data-placement="top" title="Hier können Sie ihren Raum im Gebäude finden.">MAP</button>
                            <button class="btn btn-success btn-sm" type="button" id="Zollstock" data-toggle="tooltip" data-placement="top" title="Klicken Sie für den Zollstock.">Zollstock anzeigen</button>
                            <label id="abstand-inline-label" class="abstand-anzeige" for="abstand-inputfield">Abstand in cm :</label>
                            <input id="abstand-inline-inputfield" class="abstand-anzeige" name="abstand-inputfield" />
                        </div>
                        <div class="panel-body">





                            <div id="dialog-GrundrissNeubau" title="Neubau-Grundriss">

                                <div id= "MapText">
                                    <p>Hier finden Sie eine Übersicht über den Neubau.
                                        Sie haben die Möglichkeit, Räume über eine Karte der jeweiligen Etage eines Gebäudes auszuwählen.</p>
                                </div>
                                <div id= "NeubauAuswahlMap">
                                    <fieldset>
                                        <div id="NeuTraktMap">
                                            <select class="form-control" name="NeubauTraktMap" id="NeubauTraktMap">
                                                <option value="">Gebäude</option>
                                                <?php
                                                $buildingsNewMap = $db->query("SELECT building_id, building_name FROM " . TABLE_BUILDINGS . " WHERE building_type = 2");
                                                foreach ($buildingsNewMap as $buildingNewMap) {
                                                    echo "<option value = " . $buildingNewMap['building_id'] . ">" . $buildingNewMap['building_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div id="NeuEtageMap">
                                            <select class="form-control" name="NeubauEtageMap" id="NeubauEtageMap">
                                                <option value="">Vorher Gebäude wählen...</option>
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                <div id="Map"></div>


                            </div>

                            <!-- Dropdown-Menü für den Neubau -->
                            <div id="NeubauAuswahl">

                                <div id="NeuTrakt">
                                    <select name="NeubauTrakt" class="form-control" id="NeubauTrakt">
                                        <option value="">Trakt wählen</option>
                                        <?php
                                        $buildingsNew = $db->query("SELECT building_id, building_name FROM " . TABLE_BUILDINGS . " WHERE building_type = 2");
                                        foreach ($buildingsNew as $buildingNew) {
                                            echo "<option value = " . $buildingNew['building_id'] . ">" . $buildingNew['building_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="NeuEtage">
                                    <select name="NeubauEtage" class="form-control" id="NeubauEtage">
                                        <option value="">Vorher Trakt wählen...</option>
                                    </select>
                                </div>
                                <div id="NeuRaum">
                                    <select name="NeubauRaum" class="form-control" id="NeubauRaum">
                                        <option value="">Vorher Etage wählen...</option>
                                    </select>
                                </div>

                            </div>
                            <!-- Ende Dropdown-Menü für Neubau -->

                            <!--NeubauMap-->
                            <div>
                                <div id="NeubauMap"></div>
                            </div>

                        </div>
                    </div>


                </div>
            </div>


            <!--Lager-->

            <div class="col-xs-2">


                <div class="panel panel-default">
                    <div class="panel-heading">Lager</div>
                    <div class="panel-body">
                        <div id="LagerListe"></div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Müll</div>
                    <div class="panel-body">
                        <div id="MuellListe"></div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Öffentliches Lager</div>
                    <div class="panel-body">
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