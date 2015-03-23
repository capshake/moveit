<?php
include_once '../../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    $headerTitle = 'Adminpanel - MapEditor';

    include_once '../../include/header.php';
    include_once '../include/menu.php';

    include_once '../include/classes/mapEditor.class.php';
    $mapData = new mapEditor();
    ?>

    <div class="container">
        <?php
        $existsBuilding = $db->row("SELECT building_id FROM " . TABLE_BUILDINGS, PDO::FETCH_NUM);
        if (!$existsBuilding) {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="well">
                        <div class="alert alert-info">Es muss zuerst ein Gebäude existieren.</div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            if (isset($_GET['remove'])) {
                   $mapIdBeforeDelete = $db->row("SELECT map_id FROM " . TABLE_MAPS . " WHERE map_id = :map_id", array("map_id" => $_GET['remove']));
                $numberOfSuccessfullyDeletedMaps = $db->query("DELETE FROM " . TABLE_MAPS . " WHERE map_id = :map_id", array("map_id" => $_GET['remove']), PDO::FETCH_NUM);

                if ($numberOfSuccessfullyDeletedMaps == 1) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">Die Map wurde gelöscht.</div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">Der Löschvorgang war nicht erfolgreich.</div>
                        </div>
                    </div>
                <?php }
            }
            if (isset($_GET['edit'])) {
                $existsMap = $db->row("SELECT map_id FROM " . TABLE_MAPS . " WHERE map_id = :map_id", array("map_id" => $_GET['edit']), PDO::FETCH_NUM);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="mapHeadline">Map bearbeiten</h2> <button id="helpButton2" class="btn btn-default btn-xs">?</button>
                        <div id="mapBearbeitenDialog" title="Hilfe bei der Map-Bearbeitung">
                            <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            Hier steht Hilfetext für den eigentlichen MapEditor!!!
                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                            <p>
                                In diesem Teil findet die eigentliche Festlegung der Räume auf dem hochgeladenen Grundriss statt. Ihnen werden
                                zur Administration drei Hauptfunktionen unter den drei Tabs gegeben. 
                                <ol>
                                    <li><strong>Grundeinstellung:</strong> Laden Sie bitte zunächst eine Bild-Datei des Grundrisses hoch, diesen wählen Sie über <em>Datei auswählen</em>.
                                    Pro Etage wird ein Grundriss hochgeladen. Die hochgeladene Bild-Datei weisen Sie mit Hilfe der Dropdown-Menüs der entsprechenden Gebäude-Etage-Kombination zu.
                                    Dieser Zustand wird über den <em>Speicher-Button</em> gesichert. <em>Zurück zur Map-Übersicht</em> bringt Sie zurück zur vorherigen Seite.</li> 
                                    <li><strong>Maßstab festlegen:</strong> Diese Funktion ist essentiell dafür, dass in moveIT Maßstabsgetreu gearbeitet werden kann. Der Maßstab wird festgesetzt indem Sie
                                        an zwei Stellen in dem hochgeladenen Grundriss klicken. Es erscheinen zwei rote Winkel. Per Drag-and-Drop können Sie die Winkel in einen gewünschten Abstand setzen,
                                        das kann z.B. entlang der Länge eines Raumes sein, von dem Sie die Maße kennen. In den zwei oberen Feldern wird Ihnen links der <em>Pixel</em>-Abstand und rechts
                                        der zu bestimmende <em>cm</em>-Abstand angezeigt. Geben Sie den bekannten cm-Abstand an, sobald Sie den richtigen Abstand in der Karte mit den Winkeln markiert haben
                                        (d.h. wie in unserem Beispiel oben: die Länge des Raums wird mit den Winkeln eingespannt - die cm werden auf die Länge des Raums in cm gesetzt).</li>
                                    <li><strong>Räume platzieren:</strong> Im letzten Schritt können Sie die Räume, die Sie der Map unter <em>Räume</em> zuweisen, über den Button <em>Raum hinzufügen</em>
                                        auf die Map setzen. Es erscheint ein Rechteck, dass Sie auf den gewünschten Raum in der Map setzen und gemäß dessen Maße skalieren. Der Map-Editor speichert Ihre
                                        Änderungen automatisch.
                                    </li>
                                </ol>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="well">
                            <?php if (!$existsMap) { ?>

                                <div class="alert alert-info">Die Map existiert nicht.</div>

                                <?php
                            } else { ?>

                                <ul class="nav nav-tabs">
                                    <li <?php echo!isset($_GET['rooms']) && !isset($_GET['scale']) ? ' class="active"' : ''; ?>><a href="<?php echo BASEDIR; ?>admin/mapEditor/edit/<?php echo $_GET['edit']; ?>">Grundeinstellungen</a></li>

                                    <?php
                                    $db->bind("id", $_GET['edit']);
                                    if ($db -> single("SELECT building_type FROM " . TABLE_MAPS . " LEFT JOIN " . TABLE_BUILDINGS ." ON map_building_id = building_id WHERE map_id = :id") == 2){?>
                                        <li <?php echo isset($_GET['scale']) ? ' class="active"' : ''; ?>><a href="<?php echo BASEDIR; ?>admin/mapEditor/edit/<?php echo $_GET['edit']; ?>/scale">Maßstab festlegen</a></li>
                                        <li <?php echo isset($_GET['rooms']) ? ' class="active"' : ''; ?>><a href="<?php echo BASEDIR; ?>admin/mapEditor/edit/<?php echo $_GET['edit']; ?>/rooms">Räume platzieren</a></li>
                                        <?php
                                    }?>

                                    <li class="pull-right"><a href="<?php echo BASEDIR; ?>admin/mapEditor"> zurück zur Mapübersicht</a></li>
                                </ul>

                                <?php
                                //MAßSTAB
                                if (isset($_GET['scale'])) {

                                    if (isset($_POST['scale'])) {
                                        $update = json_decode($mapData->updateMapScale($_POST, $_GET['edit']));
                                        if ($update->status == 'error') {
                                            ?>
                                            <div class="row">
                                                <div class="col-md-10 col-md-offset-1">

                                                    <div class="alert alert-danger"><?php echo $update->msg; ?></div>

                                                </div>
                                            </div>
                                            <?php
                                        }
                                        if ($update->status == 'success') {
                                            ?>
                                            <div class="row">
                                                <div class="col-md-10 col-md-offset-1">

                                                    <div class="alert alert-success"><?php echo $update->msg; ?></div>

                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    $db->bind("id", $_GET['edit']);
                                    $map = $db->row("SELECT * FROM " . TABLE_MAPS . " WHERE map_id = :id");
                                    if (empty($map['map_picture'])) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-10 col-md-offset-1">

                                                <div class="alert alert-info">Es muss zuerst ein Grundriss unter "Grundeinstellungen" hochgeladen werden.</div>

                                            </div>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <form method="POST" action="<?php echo BASEDIR; ?>admin/mapEditor/edit/<?php echo $map['map_id']; ?>/scale" role="form" enctype="multipart/form-data">

                                            <div class="row">
                                                <div class="col-md-4 col-md-offset-1">

                                                    <div class="form-group">
                                                        <label for="map_scale_px">Distanz in Pixel</label>
                                                        <input id="map_scale_px" name="map_scale_px" value="<?php echo $map['map_scale_px']; ?>" class="form-control scale-value" placeholder="Distanz in Pixel" type="text" required autofocus>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">

                                                    <div class="form-group">
                                                        <label for="map_scale_cm">Pixel in cm</label>
                                                        <input id="map_scale_cm" name="map_scale_cm" value="<?php echo $map['map_scale_cm']; ?>" class="form-control" placeholder="Pixel in cm" type="text" required autofocus>
                                                    </div>

                                                </div>
                                                <div class="col-md-3">
                                                    <br />
                                                    <button class="btn btn-primary" type="submit" name="scale">Speichern</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="groundplan-outer">
                                                    <div class="groundplan scale">

                                                        <div class="groundplan-inner">
                                                            <img src="<?php echo BASEDIR . $map['map_picture']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    //RÄUME VERTEILEN
                                } else if (isset($_GET['rooms'])) {
                                    $db->bind("id", $_GET['edit']);
                                    $map = $db->row("SELECT * FROM " . TABLE_MAPS . " WHERE map_id = :id");

                                    if (empty($map['map_picture'])) {
                                        ?><br />
                                        <div class="alert alert-info">Es muss zuerst ein Grundriss unter "Grundeinstellungen" hochgeladen werden.</div>
                                        <?php
                                    } else if (empty($map['map_scale_px']) || empty($map['map_scale_cm'])) {
                                        ?><br />
                                        <div class="alert alert-info">Es muss zuerst ein Maßstab definiert werden.</div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="save-groundplan">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-info">Gestalten Sie hier den Lageplan dieser Etage. Bitte dazu die Hinweise unten beachten!</div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="contextMenu dropdown clearfix" style="display:none;">
                                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
                                                <li><a tabindex="-1" href="#">Entfernen</a></li>
                                            </ul>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-5 col-md-offset-5">
                                                <button class="add-room-groundplan-button btn btn-success">Raum hinzufügen</button>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="groundplan-outer">
                                                    <div class="groundplan" data-mapid="<?php echo $map['map_id']; ?>">

                                                        <div class="groundplan-inner">
                                                            <img src="<?php echo BASEDIR . $map['map_picture']; ?>">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">

                                            </div>
                                        </div>
                                        <?php
                                    }
                                    //EDITIEREN
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-4">
                                            <form method="POST" action="<?php echo BASEDIR; ?>admin/mapEditor/edit/<?php echo $_GET['edit']; ?>" role="form" enctype="multipart/form-data">
                                                <?php
                                                if (isset($_POST['edit'])) {
                                                    $update = json_decode($mapData->updateMap($_POST, $_FILES, $_GET['edit']));
                                                    if ($update->status == 'error') {
                                                        ?>
                                                        <div class="alert alert-danger"><?php echo $update->msg; ?></div>
                                                        <?php
                                                    }
                                                    if ($update->status == 'success') {
                                                        ?>
                                                        <div class="alert alert-success"><?php echo $update->msg; ?></div>
                                                        <?php
                                                    }
                                                }

                                                $db->bind("id", $_GET['edit']);
                                                $map = $db->row("SELECT " .TABLE_MAPS . ".*, building_type FROM " . TABLE_MAPS . " LEFT JOIN " . TABLE_BUILDINGS ." ON map_building_id = building_id WHERE map_id = :id");


                                                if($map['building_type'] == 2){
                                                    echo '<div class="form-group">';
                                                    echo '<label for="map_picture">Grundriss</label>';
                                                    echo '<input name="map_picture" type="file" />';
                                                    if (!empty($map['map_picture'])) {

                                                        echo '<br />';
                                                        echo '<a href="#" class="thumbnail">';
                                                            echo "<img src= " . BASEDIR . $map['map_picture'] . " />";
                                                        echo '</a>';
                                                    }
                                                echo '</div>';
                                                }?>

                                                <div class="form-group">
                                                    <label for="map_building_id">Gebäude</label>
                                                    <select id="map_building_id" class="form-control" name="map_building_id">
                                                        <?php
                                                        $buildings = $db->query("SELECT * FROM " . TABLE_BUILDINGS);
                                                        foreach ($buildings as $building) {
                                                            ?>
                                                            <option value="<?php echo $building['building_id']; ?>"<?php echo ($building['building_id'] == $map['map_building_id']) ? ' selected="selected"' : ''; ?>><?php echo $building['building_name']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="map_floor">Etage</label>
                                                    <select id="map_floor" class="form-control" name="map_floor">
                                                        <?php
                                                        for ($i = -5; $i < 6; $i++) {
                                                            ?>
                                                            <option value="<?php echo $i; ?>"<?php echo ($i == $map['map_floor']) ? ' selected="selected"' : ''; ?>><?php echo getFloor($i); ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>


                                                <button class="btn btn-primary" type="submit" name="edit">Speichern</button>
												<a href="<?php echo BASEDIR; ?>admin/mapEditor" class="btn btn-default pull-right">
													<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> zurück zur Mapübersicht
												</a>
                                            </form>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            } else if (isset($_GET['create'])) {
                ?>
                <div class="row">
                    <div class="col-md-offset-4 col-md-4">
                        <h2>Map erstellen</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-4 col-md-4">
                        <div class="well">
                            <form method="POST" action="<?php echo BASEDIR; ?>admin/mapEditor/create" role="form" enctype="multipart/form-data">

                                <?php
                                $map_building_id = '';
                                $map_floor = '';
                                if (isset($_POST['create'])) {
                                    $map_building_id = $_POST['map_building_id'];
                                    $map_floor = $_POST['map_floor'];

                                    $update = json_decode($mapData->createMap($_POST, null));

                                    if ($update->status == 'error') {
                                        ?>
                                        <div class="alert alert-danger"><?php echo $update->msg; ?></div>
                                        <?php
                                    }
                                    if ($update->status == 'success') {
                                        ?>
                                        <div class="alert alert-success"><?php echo $update->msg; ?></div>
                                        <?php
                                    }
                                }
                                ?>

                                <div class="form-group">
                                    <label for="map_building_id">Gebäude</label>
                                    <select id="map_building_id" class="form-control" name="map_building_id">
                                        <?php
                                        $buildings = $db->query("SELECT * FROM " . TABLE_BUILDINGS);
                                        foreach ($buildings as $building) {
                                            ?>
                                            <option value="<?php echo $building['building_id']; ?>"<?php echo ($building['building_id'] == $map_building_id) ? ' selected="selected"' : ''; ?>><?php echo $building['building_name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="map_floor">Etage</label>
                                    <select id="map_floor" class="form-control" name="map_floor">
                                        <?php
                                        for ($i = -5; $i < 6; $i++) {
                                            ?>
                                            <option value="<?php echo $i; ?>"<?php echo ($i == $map_floor) ? ' selected="selected"' : ''; ?>><?php echo getFloor($i); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <button class="btn btn-primary" type="submit" name="create">Speichern</button>
                                <a href="<?php echo BASEDIR; ?>admin/mapEditor" class="btn btn-default pull-right">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> zurück zur Mapübersicht
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                $mapList = $db->query("SELECT map_id, map_building_id, map_floor, map_picture, map_scale_cm, map_scale_px, building_name, building_type FROM " . TABLE_MAPS . " LEFT JOIN " . TABLE_BUILDINGS . " ON map_building_id = building_id ORDER BY map_id");

                ?>
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="mapHeadline">Map Editor</h2> <button btn id="helpButton"class="btn btn-default btn-xs">?</button>

                        <div id="mapEditorDialog" title="Hilfe für den Map Editor">
                        <p>
                            Damit Sie an dieser Stelle einen Grundriss hochladen können, müssen im Vorfeld folgende Schritte erfolgt sein:
                            <ol>
                                <li>Sie müssen unter <em>Gebäude</em> (siehe Navigationsleiste) ein Gebäude erstellt haben, dass vom Typ "Neubau" ist.</li>
                                <li>Nachdem Sie dieses Gebäude angelegt haben, können Sie unter <em>Map Editor</em> die Etagen dieses Gebäudes anlegen (Etage für Etage).</li>
                            </ol>
                            <hr>
                            <h3>Grundriss hochladen</h3>
                            Die Listeneinträge auf dieser Seite verfügen über je zwei Buttons (Bearbeiten und Löschen). Wenn man auf den <em>Bearbeiten</em>-Button eines Neubau-Eintrags
                            klickt, kann eine Bild-Datei des Grundrisses hochgeladen werden.
                        </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="well">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Gebäudename</th>
                                        <th>Etage</th>
                                        <th>Alt-/Neubau</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($mapList as $map) {
                                        ?>

                                        <tr>
                                            <td><?php echo $map['building_name']; ?></td>
                                            <td><?php echo getFloor($map['map_floor']); ?></td>
                                            <td><?php echo ($map['building_type'] == 1) ? 'Altbau' : 'Neubau'; ?></td>
                                            <td class="text-right">
                                                <a href="<?php echo BASEDIR; ?>admin/mapEditor/edit/<?php echo $map['map_id']; ?>" class="btn btn-default btn-xs">
                                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                </a>
                                                <a href="<?php echo BASEDIR; ?>admin/mapEditor/remove/<?php echo $map['map_id']; ?>" class="btn btn-danger btn-xs delete-button">
                                                    <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <a href="<?php echo BASEDIR; ?>admin/mapEditor/create" class="btn btn-success">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Etage anlegen
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>


    </div><!-- /.container -->

    <?php
    $javascript = '<script src="' . BASEDIR . 'js/groundplan.js"></script>';
    include_once '../../include/footer.php';
} else {
    header('location: ' . BASEDIR . 'login');
}

