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
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Hinweis:</strong> Sie können aus diesem Bereich verschobene Möbel nicht wieder zurück in den Altbau schieben! Bitte nutzen Sie Ihr persönliches Lager auf der rechten Seite.
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
                            <button class="btn btn-success btn-sm" type="button" id="GrundrissNeubau" data-toggle="tooltip" data-placement="top" title="Hier können Sie ihren Raum im Gebäude finden">MAP</button>
                            <button class="btn btn-success btn-sm" type="button" id="Zollstock" data-toggle="tooltip" data-placement="top" title="Beim Klicken erscheinen zwei bewegliche Winkel zum Ausmessen des Raums">Zollstock anzeigen</button>
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
                                                $maps = $db->query("SELECT map_id, map_building_id, map_floor, map_picture, map_scale_cm, map_scale_px, building_name FROM " . TABLE_MAPS . " LEFT JOIN " . TABLE_BUILDINGS . " ON map_building_id = building_id WHERE building_type = 2 AND map_picture != ''");
                                                foreach ($maps as $map) {
                                                    echo "<option value = " . $map['map_id'] . ">" . $map['building_name'] . " - " . getFloor($map['map_floor']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                                <div id="Map">
                                    <div class="alert alert-info">Wählen Sie eine Map aus.</div>
                                </div>


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
                    <div class="panel-heading">Aussortierte Möbel</div>
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
    </div><!-- /.container -->

    <?php
    include_once 'include/footer.php';
}
