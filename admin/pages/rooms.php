<?php
include_once '../../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    $headerTitle = 'Adminpanel - Raum';

    include_once '../../include/header.php';
    include_once '../include/menu.php';

    include_once '../include/classes/rooms.class.php';
    $roomData = new Rooms();
    ?>

    <div class="container">
        <?php
        if (isset($_GET['remove'])) {
            $roomNameBeforeDelete = $db->row("SELECT room_name FROM " . TABLE_ROOMS . " WHERE room_id = :room_id", array("room_id" => $_GET['remove']));

            if($db -> single("SELECT COUNT(*) FROM " . TABLE_ROOMS . ", " . TABLE_ITEMS . " WHERE item_room_id = room_id AND (room_id = :room_id OR item_origin_room = :room_id_origin)", array("room_id" => $_GET['remove'], "room_id_origin" => $_GET['remove'])) > 0){
                if($db -> single("SELECT COUNT(*) FROM " . TABLE_ROOMS . ", " . TABLE_ITEMS . " WHERE item_room_id = room_id AND room_id = :room_id", array("room_id" => $_GET['remove'])) > 0){
                    $numberOfSuccessfullyDeletedRooms = 0;
                    $roomNotEmpty = 1;
                }else{
                    $numberOfSuccessfullyDeletedRooms = $db -> query("UPDATE " . TABLE_ROOMS . " SET room_type = 0 WHERE room_id = :room_id", array("room_id" => $_GET['remove']));
                }
            }else{
                $numberOfSuccessfullyDeletedRooms = $db->query("DELETE FROM " . TABLE_ROOMS . " WHERE room_id = :room_id", array("room_id" => $_GET['remove']), PDO::FETCH_NUM);
            }

			if ($numberOfSuccessfullyDeletedRooms == 1) { ?>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success">Der Raum <em><?php echo $roomNameBeforeDelete['room_name'] ?></em> wurde gelöscht.</div>
					</div>
				</div>
			<?php } else if($roomNotEmpty){ ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger">Der Raum <em><?php echo $roomNameBeforeDelete['room_name'] ?></em> wurde <b>NICHT</b> gelöscht, da er noch Möbelstücke enthält.</div>
                    </div>
                </div>
            <?php }else { ?>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger">Der Löschvorgang war nicht erfolgreich.</div>
					</div>
				</div>
			<?php }
        }
        if (isset($_GET['edit'])) {
            $existsRoom = $db->row("SELECT room_id FROM " . TABLE_ROOMS . " WHERE room_id = :room_id", array("room_id" => $_GET['edit']), PDO::FETCH_NUM);
            ?>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <h2>Raum bearbeiten</h2><br />
                    <div class="well">
                        <?php if (!$existsRoom) { ?>
                            <div class="alert alert-info">Der Raum existiert nicht!</div>
                        <?php } else { ?>
                            <form method="POST" action="<?php echo BASEDIR; ?>admin/rooms/edit/<?php echo $_GET['edit']; ?>" role="form">

                                <?php
                                if (isset($_POST['edit'])) {
                                    $update = json_decode($roomData->updateRoom($_POST, $_GET['edit']));
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
                                $room = $db->row("SELECT * FROM " . TABLE_ROOMS . " WHERE room_id = :id");
                                ?>


                                <div class="form-group">
                                    <label for="room_name">Raumname</label>
                                    <input id="room_name" name="room_name" value="<?php echo $room['room_name']; ?>" class="form-control" placeholder="Raumname" type="text" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="room_name_alt">Raumname</label>
                                    <input id="room_name_alt" name="room_name_alt" value="<?php echo $room['room_name_alt'] ?>" class="form-control" placeholder="Alternativer Raumname (z.B. PPD-Nr)" type="text" autofocus>
                                </div>

                                <?php /*<div class="form-group">
                                    <label for="room_map_id">Map</label>
                                    <select id="room_map_id" class="form-control" name="room_map_id">
                                        <?php
                                        $maps = $db->query("SELECT map_id, map_building_id, map_floor, map_picture, map_scale_cm, map_scale_px, building_name FROM " . TABLE_MAPS . " LEFT JOIN " . TABLE_BUILDINGS . " ON map_building_id = building_id ORDER BY map_id");
                                        foreach ($maps as $map) {
                                            ?>
                                            <option value="<?php echo $map['map_id']; ?>"<?php echo ($map['map_id'] == $room['room_map_id']) ? ' selected="selected"' : ''; ?>><?php echo $map['building_name']; ?> <?php echo getFloor($map['map_floor']); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>* ?>
                                <?php /*<div class="form-group">
                                    <label for="room_type">Wo befindet sich der Raum?</label>
                                    <select id="room_type" class="form-control" name="room_type">
                                        <option value="1" <?php echo ($room['room_type'] == 1) ? 'selected' : ''; ?>>Altbau</option>
                                        <option value="2" <?php echo ($room['room_type'] == 2) ? 'selected' : ''; ?>>Neubau</option>
                                    </select>
                                </div> */ ?>


                                <button class="btn btn-primary" type="submit" name="edit">Speichern</button>
                            </form>

                        <?php } ?>
                        <br />

                        <a href="<?php echo BASEDIR; ?>admin/rooms" class="btn btn-default">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> zurück zur Raumübersicht
                        </a>
                    </div>
                </div>
            </div>
            <?php
        } else if (isset($_GET['create'])) {
            ?>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <h2>Raum erstellen</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <div class="well">
                        <form method="POST" action="<?php echo BASEDIR; ?>admin/rooms/create" role="form">

                            <?php
                            $room_name = '';

                            if (isset($_POST['create'])) {
                                $room_name = $_POST['room_name'];


                                $update = json_decode($roomData->createRoom($_POST));
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
                                <label for="room_name">Raumname</label>
                                <input id="room_name" name="room_name" value="<?php echo $room_name; ?>" class="form-control" placeholder="Raumname" type="text" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="room_name_alt">Raumname</label>
                                <input id="room_name_alt" name="room_name_alt" value="<?php echo $room_name; ?>" class="form-control" placeholder="Alternativer Raumname (z.B. PPD-Nr)" type="text" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="room_map_id">Map</label>
                                <select id="room_map_id" class="form-control" name="room_map_id">
                                    <?php
                                    $maps = $db->query("SELECT map_id, map_building_id, map_floor, map_picture, map_scale_cm, map_scale_px, building_name FROM " . TABLE_MAPS . " LEFT JOIN " . TABLE_BUILDINGS . " ON map_building_id = building_id ORDER BY map_id");
                                    foreach ($maps as $map) {
                                        ?>
                                        <option value="<?php echo $map['map_id']; ?>"><?php echo $map['building_name']; ?> <?php echo getFloor($map['map_floor']); ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <button class="btn btn-primary" type="submit" name="create">Speichern</button>
                        </form>

                        <br />
                        <a href="<?php echo BASEDIR; ?>admin/rooms" class="btn btn-default">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> zurück zur Raumübersicht
                        </a>
                    </div>
                </div>
            </div>
            <?php
        } else {
            $roomList = $db->query("SELECT * FROM " . TABLE_ROOMS . " WHERE room_type != 0");
            ?>
            <div class="row">
                <div class="col-md-12">
                    <h2>Räume</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="well">
                        <?php
                        if (!$roomList) {
                            ?>
                            <div class="alert alert-info">Es wurden noch keine Räume erstellt.</div>
                            <?php
                        } else {
                            ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Raumname</th>
                                        <th>Alternativer Raumname</th>
                                        <th>Wo ist der Raum?</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($roomList as $room) {
                                        ?>

                                        <tr>
                                            <td><?php echo $room['room_name']; ?></td>
                                            <td><?php echo $room['room_name_alt']; ?></td>
                                            <td><?php echo ($room['room_type'] == 1) ? 'Altbau' : 'Neubau'; ?></td>
                                            <td class="text-right">
                                                <a href="<?php echo BASEDIR; ?>admin/rooms/edit/<?php echo $room['room_id']; ?>" class="btn btn-default btn-xs">
                                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                </a>
                                                <a href="<?php echo BASEDIR; ?>admin/rooms/remove/<?php echo $room['room_id']; ?>" class="btn btn-danger btn-xs delete-button">
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
                        <a href="<?php echo BASEDIR; ?>admin/rooms/create" class="btn btn-success">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Raum erstellen
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
