<?php
include_once '../../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    $headerTitle = 'Adminpanel - Gebäude';

    include_once '../../include/header.php';
    include_once '../include/menu.php';

    include_once '../include/classes/buildings.class.php';
    $buildingData = new Buildings();
    ?>

    <div class="container">
        
        <?php
        if (isset($_GET['remove'])) {
            
            $buildingNameBeforeDelete = $db->row("SELECT building_name FROM " . TABLE_BUILDINGS . " WHERE building_id = :building_id", array("building_id" => $_GET['remove']));
			$numberOfSuccessfullyDeletedBuilding = $db->query("DELETE FROM " . TABLE_BUILDINGS . " WHERE building_id = :building_id", array("building_id" => $_GET['remove']), PDO::FETCH_NUM);

			if ($numberOfSuccessfullyDeletedBuilding == 1) { ?>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success">Das Gebäude <em><?php echo $buildingNameBeforeDelete['building_name'] ?></em> wurde gelöscht.</div>
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
            $existsBuilding = $db->row("SELECT building_id FROM " . TABLE_BUILDINGS . " WHERE building_id = :building_id", array("building_id" => $_GET['edit']), PDO::FETCH_NUM);
            ?>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                   
                    <h2>Gebäude bearbeiten</h2> 


                    <br />
                    <div class="well">
                        <?php if (!$existsBuilding) { ?>
                            <div class="alert alert-info">Das Gebäude existiert nicht!</div>
                        <?php } else { ?>
                            <form method="POST" action="<?php echo BASEDIR; ?>admin/buildings/edit/<?php echo $_GET['edit']; ?>" role="form">

                                <?php
                                if (isset($_POST['edit'])) {
                                    $update = json_decode($buildingData->updateBuilding($_POST, $_GET['edit']));
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
                                $building = $db->row("SELECT * FROM " . TABLE_BUILDINGS . " WHERE building_id = :id");
                                ?>


                                <div class="form-group">
                                    <label for="building_name">Gebäudename</label>
                                    <input id="building_name" name="building_name" value="<?php echo $building['building_name']; ?>" class="form-control" placeholder="Gebäudename" type="text" required autofocus>
                                </div>
                                <?php /*<div class="form-group">
                                    <label for="building_type">Wo befindet sich das Gebäude?</label>
                                    <select id="building_type" class="form-control" name="building_type">
                                        <option value="1" <?php echo ($building['building_type'] == 1) ? 'selected' : ''; ?>>Altbau</option>
                                        <option value="2" <?php echo ($building['building_type'] == 2) ? 'selected' : ''; ?>>Neubau</option>
                                    </select>
                                </div>*/
                                      ?>
                                
                                <button class="btn btn-primary" type="submit" name="edit">Speichern</button>
                            </form>

                        <?php } ?>
                        <br />

                        <a href="<?php echo BASEDIR; ?>admin/buildings" class="btn btn-default">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> zurück zur Gebäudeübersicht
                        </a>
                    </div>
                </div>
            </div>
            <?php
        } else if (isset($_GET['create'])) {
            ?>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <h2>Gebäude erstellen</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <div class="well">
                        <form method="POST" action="<?php echo BASEDIR; ?>admin/buildings/create" role="form">

                            <?php
                            $building_name = '';

                            if (isset($_POST['create'])) {
                                $building_name = $_POST['building_name'];


                                $update = json_decode($buildingData->createBuilding($_POST));
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
                                <label for="building_name">Gebäudename</label>
                                <input id="building_name" name="building_name" value="<?php echo $building_name; ?>" class="form-control" placeholder="Gebäudename" type="text" required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="building_type">Wo befindet sich das Gebäude?</label>
                                <select id="building_type" class="form-control" name="building_type">
                                    <option value="1" <?php echo (@$_POST['building_type'] == 1) ? 'selected' : ''; ?>>Altbau</option>
                                    <option value="2" <?php echo (@$_POST['building_type'] == 2) ? 'selected' : ''; ?>>Neubau</option>
                                </select>
                            </div>
                            
                            <button class="btn btn-primary" type="submit" name="create">Speichern</button>
                        </form>

                        <br />
                        <a href="<?php echo BASEDIR; ?>admin/buildings" class="btn btn-default">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> zurück zur Gebäudeübersicht
                        </a>
                    </div>
                </div>
            </div>
            <?php
        } else {
            $buildingList = $db->query("SELECT * FROM " . TABLE_BUILDINGS);
            ?>
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mapHeadline">Gebäude&nbsp;</h2><button id="helpButtonBuildings" class="btn btn-default btn-xs">?</button>
                        <div id="buildingsDialog" title="Hilfe bei der Einrichtung von Gebäuden">
                            <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            Hier steht Hilfetext für den Gebäude-Editor
                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="well">
                        <?php
                        if (!$buildingList) {
                            ?>
                            <div class="alert alert-info">Es wurden noch keine Gebäude erstellt.</div>
                            <?php
                        } else {
                            ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Gebäudename</th>
                                        <th>Wo ist das Gebäude?</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($buildingList as $building) {
                                        ?>

                                        <tr>
                                            <td><?php echo $building['building_name']; ?></td>
                                            <td><?php echo ($building['building_type'] == 1) ? 'Altbau' : 'Neubau'; ?></td>
                                            <td class="text-right">
                                                <a href="<?php echo BASEDIR; ?>admin/buildings/edit/<?php echo $building['building_id']; ?>" class="btn btn-default btn-xs">
                                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                </a>
                                                <a href="<?php echo BASEDIR; ?>admin/buildings/remove/<?php echo $building['building_id']; ?>" class="btn btn-danger btn-xs delete-button">
                                                    <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        }
                        ?>
                        <a href="<?php echo BASEDIR; ?>admin/buildings/create" class="btn btn-success">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Gebäude erstellen
                        </a>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>


    </div><!-- /.container -->

    <?php
    include_once '../../include/footer.php';
} else {
    header('location: ' . BASEDIR . 'login');
}
