<?php include_once 'include/config.php'; 

if (!$userData->isLoggedIn()) { 
    header('location: ' . BASEDIR . 'login'); 
} else { 
    include_once 'include/header.php'; 
    include_once 'include/menu.php'; 
?>


<div class="container">
    <div id="MapsLagerListen" class="row">
        <div id="Altbau" class="col-md-2">
            <div class="row" id="Ueberschriften">
                <h4>Altbau</h4>
                <button type="button" id="GrundrissAltbau" data-toggle="tooltip" data-placement="top" title="Hier können Sie ihren Raum im Gebäude finden.">MAP</button>

                <div id="dialog-GrundrissAltbau" title="Altbau-Grundriss">

                    <div id="MapText">
                        <p>Hier finden Sie eine Übersicht über die AltbauMap. Sie haben die Möglichkeit Räume auszuwählen, indem Sie über die Map navigieren.</p>
                    </div>
                    <div id="AltbauAuswahlMap" class="row">
                        <fieldset>
                            <div id="AltTraktMap" class="col-md-1">
                                <select name="AltbauTraktMap" id="AltbauTraktMap">
                                    <option value="EOG">Trakt1</option>
                                    <option value="1OG">Trakt2</option>
                                    <option value="2OG">Trakt3</option>
                                </select>
                            </div>
                            <div id="AltEtageMap" class="col-md-4">
                                <select name="AltbauEtageMap" id="AltbauEtageMap">
                                    <option value="EOG">Erdgeschoss</option>
                                    <option value="1OG">Erstes Stockwerk</option>
                                    <option value="2OG">Zweites Stockwerk</option>
                                </select>
                            </div>
                            <div id="AltRaumMap" class="col-md-1">
                                <select name="AltbauRaumMap" id="AltbauRaumMap">
                                    <option value="EOG">Raum1</option>
                                    <option value="1OG">Raum2</option>
                                    <option value="2OG">Raum3</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>
                    <div id="Map">
                        mapmap
                    </div>
                </div>
            </div>
            <!--Dropdown Menue fuer den Altbau-->
            <div id="AltbauAuswahl" class="row">
                <fieldset class="col-md-12">
                    <div id="AltTrakt" class="row">
                        <select name="AltbauTrakt" id="AltbauTrakt">
                            <option value="EOG">Trakt1</option>
                            <option value="1OG">Trakt2</option>
                            <option value="2OG">Trakt3</option>
                        </select>
                    </div>
                    <div id="AltEtage" class="row">
                        <select name="AltbauEtage" id="AltbauEtage">
                            <option value="EOG">Erdgeschoss</option>
                            <option value="1OG">Erstes Stockwerk</option>
                            <option value="2OG">Zweites Stockwerk</option>
                        </select>
                    </div>
                    <div id="AltRaum" class="row">
                        <select name="AltbauRaum" id="AltbauRaum">
                            <option value="EOG">Raum1</option>
                            <option value="1OG">Raum2</option>
                            <option value="2OG">Raum3</option>
                        </select>
                    </div>
                </fieldset>
            </div>



            <!--AltbauListe-->
            <div class="row">
                <h5 class="altlist">In diesem Raum befinden sich:</h5>
                <ul id="AltbauListe" data-toggle="tooltip" data-placement="top" title="Ziehen Sie die Items in den Raum.">
                    <li class="ui-state-default" data-type="tisch" data-count="4">Tisch <span id="tischAnzahl">4</span>x</li>
                    <li class="ui-state-default" data-type="stuhl" data-count="20">Stuhl 20x</li>
                    <li class="ui-state-default" data-type="sonstiges" data-count="1">Beamer 1x</li>
                    <li class="ui-state-default" data-type="tafel" data-count="1">Whiteboard <span>1</span>x</li>
                    <li class="ui-state-default" data-type="drehstuhl" data-count="1">Drehstuhl 1x</li>
                </ul>
            </div>
        </div>
        <div id="Neubau" class="col-md-7">
            <!--Dropdown Menue fuer den Neubau-->
            <div class="row" id="Ueberschriften">
                <h4>Neubau</h4>
                <button type="button" id="GrundrissNeubau" data-toggle="tooltip" data-placement="top" title="Hier können Sie ihren Raum im Gebäude finden.">MAP</button>
                <button type="button" id="Zollstock" data-toggle="tooltip" data-placement="top" title="Klicken Sie für den Zollstock.">Zollstock</button>

                <div id="dialog-GrundrissNeubau" title="Neubau-Grundriss">

                    <div id="MapText">
                        <p>Hier finden Sie eine Übersicht über die NeubauMap. Sie haben die Möglichkeit Räume auszuwählen, indem Sie über die Map navigieren.</p>
                    </div>
                    <div id="NeubauAuswahlMap" class="row">
                        <fieldset>
                            <div id="NeuTraktMap" class="col-md-1">
                                <select name="NeubauTraktMap" id="NeubauTraktMap">
                                    <option value="EOG">Trakt1</option>
                                    <option value="1OG">Trakt2</option>
                                    <option value="2OG">Trakt3</option>
                                </select>
                            </div>
                            <div id="NeuEtageMap" class="col-md-4">
                                <select name="NeubauEtageMap" id="NeubauEtageMap">
                                    <option value="EOG">Erdgeschoss</option>
                                    <option value="1OG">Erstes Stockwerk</option>
                                    <option value="2OG">Zweites Stockwerk</option>
                                </select>
                            </div>
                            <div id="NeuRaumMap" class="col-md-1">
                                <select name="NeubauRaumMap" id="NeubauRaumMap">
                                    <option value="EOG">Raum1</option>
                                    <option value="1OG">Raum2</option>
                                    <option value="2OG">Raum3</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>
                    <div id="Map">
                        mapmap
                    </div>


                </div>
            </div>

            <div id="NeubauAuswahl" class="row">
                <fieldset>
                    <div id="NeuTrakt" class="col-md-1">
                        <select name="NeubauTrakt" id="NeubauTrakt">
                            <option value="EOG">Trakt1</option>
                            <option value="1OG">Trakt2</option>
                            <option value="2OG">Trakt3</option>
                        </select>
                    </div>
                    <div id="NeuEtage" class="col-md-3">
                        <select name="NeubauEtage" id="NeubauEtage">
                            <option value="EOG">Erdgeschoss</option>
                            <option value="1OG">Erstes Stockwerk</option>
                            <option value="2OG">Zweites Stockwerk</option>
                        </select>
                    </div>
                    <div id="NeuRaum" class="col-md-1">
                        <select name="NeubauRaum" id="NeubauRaum">
                            <option value="EOG">Raum1</option>
                            <option value="1OG">Raum2</option>
                            <option value="2OG">Raum3</option>
                        </select>
                    </div>
                </fieldset>

            </div>


            <!--NeubauMap-->
            <div>
                <div id="NeubauMap" class="row">
                    <img src="./img/item-types/stuhl.svg" class="moveitplaner">
                    <img src="./img/item-types/stuhl.svg" class="moveitplaner">
                    <img src="./img/item-types/drehstuhl.svg" class="moveitplaner">
                </div>
            </div>

        </div>


        <!--Lager-->

        <div id="LagerLeiste" class="col-md-2">
            <div id="ObereLeiste" class="row">
                <ul>
                    <!-- Tabs: Lager, Wunsch, Müll -->
                    <li class="col-md-3"><a href="#Lager" data-toggle="tooltip" data-placement="top" title="Verschieben Sie Möbelstücke per Drag&Drop in Ihr persönliches Lager" id="LagerTab"> Lager </a>
                    </li>
                    <li class="col-md-4"><a href="#Wunschliste" data-toggle="tooltip" data-placement="top" title="Klicken um Möbelwunsch aufzugeben" id="WunschTab"> Wunsch</a>
                    </li>
                    <li class="col-md-3"><a href="#Müll" data-toggle="tooltip" data-placement="top" title="Verschieben Sie Möbelstücke per Drag&Drop in den Müll" id="MüllTab">Müll</a>
                    </li>

                </ul>
                <div id="Lager">
                    <ul id="LagerListe" class="col-md-12">
                        <li class="ui-state-default" data-type="stuhl" data-count="20">Stuhl 20x</li>
                        <li class="ui-state-default" data-type="sonstiges" data-count="1">Beamer 1x</li>
                    </ul>
                </div>

                <div id="Wunschliste">

                    <!--Tabelle der Wunschliste -->
                    <div id="wunschtabelle" class="ui-widget">
                        <table id="wünsche" class="ui-widget ui-widget-content">
                            <thead>
                                <tr class="ui-widget-header ">
                                    <th id="Button"></th>
                                    <th>Bezeichnung</th>
                                    <th>Anzahl</th>
                                    <th>Länge (cm)</th>
                                    <th>Breite (cm)</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>



                    </div>
                    <!-- Buttons der Wunschliste -->
                    <button type="button" id="Wunschhinzufügen">Hinzufügen</button>
                </div>

                <!--        Dialogfenster-->
                <div id="wunschdialogform" title="Wunsch-Item Hinzufügen">
                    <p class="validateTips"></p>
                    <form>
                        <fieldset>
                            <label for="bezeichnung">Bezeichnung</label>
                            <input type="text" name="bezeichnung" id="bezeichnung" class="text ui-widget-content ui-corner-all">
                            <label for="anzahl">Anzahl</label>
                            <input type="number" name="anzahl" id="anzahl" class="text ui-widget-content ui-corner-all">
                            <label for="länge">Länge (cm)</label>
                            <input type="number" name="länge" id="länge" class="text ui-widget-content ui-corner-all">
                            <label for="breite">Breite (cm)</label>
                            <input type="number" name="breite" id="breite" class="text ui-widget-content ui-corner-all">
                            <!-- Allow form submission with keyboard without duplicating the dialog button-->
                            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
                        </fieldset>
                    </form>
                </div>

                <div id="dialog-Itemlöschen" title="Löschen">
                    Item wirklich löschen?
                </div>

                <div id="dialog-Itembearbeiten" title="Bearbeiten">
                    <p class="validateTips"></p>
                    <form>
                        <fieldset>
                            <label for="bezeichnung">Bezeichnung</label>
                            <input type="text" name="bezeichnung" id="bezeichnung" class="text ui-widget-content ui-corner-all">
                            <label for="anzahl">Anzahl</label>
                            <input type="number" name="anzahl" id="anzahl" class="text ui-widget-content ui-corner-all">
                            <label for="länge">Länge (cm)</label>
                            <input type="number" name="länge" id="länge" class="text ui-widget-content ui-corner-all">
                            <label for="breite">Breite (cm)</label>
                            <input type="number" name="breite" id="breite" class="text ui-widget-content ui-corner-all">
                            <!-- Allow form submission with keyboard without duplicating the dialog button-->
                            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
                        </fieldset>
                    </form>
                </div>

                <!--            Ende Dialogfenster-->

                <div id="Müll">
                    <ul id="MuellListe" class="col-md-12">
                        <li class="ui-state-default" data-type="tafel" data-count="1">Whiteboard 1x</li>
                        <li class="ui-state-default" data-type="drehstuhl" data-count="1">Drehstuhl 1x</li>
                    </ul>

                </div>
            </div>
            <div id="oeffentlichesLager" class="row">
                <div class="col-md-12" id="oeffentlichesLagerUeberschrift">
                    <label class="col-md-10">öffentliches Lager</label>

                    <button type="button" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-repeat"></span>
                    </button>

                </div>

                <ul id="oeffentlichesLagerListe" class="col-md-12">
                    <li class="ui-state-default" data-type="tisch" data-count="1">Tisch 11x</li>
                    <li class="ui-state-default" data-type="stuhl" data-count="1">Stuhl 20x</li>
                    <li class="ui-state-default" data-type="sonstiges" data-count="1">Beamer 1x</li>
                    <li class="ui-state-default" data-type="tafel" data-count="1">Whiteboard 1x</li>
                    <li class="ui-state-default" data-type="drehstuhl" data-count="1">Drehstuhl 1x</li>
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        //Funktion beim Verlassen der Seite/Aktualisieren der Seite
        window.onbeforeunload = function () {
            return "Sie verlassen die Seite ohne zu Speichern.";
        };
    </script>
</div>
<!-- /.container -->

<?php include_once 'include/footer.php'; 
}