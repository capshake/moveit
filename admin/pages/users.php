<?php
include_once '../../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    $headerTitle = 'Adminpanel - Benutzer';

    include_once '../../include/header.php';
    include_once '../include/menu.php';

    include_once '../include/classes/users.class.php';
    $adminUserData = new Users();
    ?>


    <div class="container">


        <?php
        if (isset($_GET['remove'])) {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">Benutzer wurde gelöscht</div>
                </div>
            </div>
            <?php
        }
        if (isset($_GET['edit'])) {
            $existsUser = $db->row("SELECT user_id FROM " . TABLE_USERS . " WHERE user_id = :user_id", array("user_id" => $_GET['edit']), PDO::FETCH_NUM);
            ?>
            <div class="row">
                <div class="col-md-12">
                    <h1>Benutzer bearbeiten</h1><br />
                </div>
            </div>


            <?php if (!$existsUser) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">Der Benutzer existiert nicht!</div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="row">
                    <div class="col-md-5">
                        <form method="POST" action="<?php echo BASEDIR; ?>admin/users/edit/<?php echo $_GET['edit']; ?>" role="form">

                            <?php
                            if (isset($_POST['edit'])) {
                                $update = json_decode($userData->updateUser($_POST, true, $_GET['edit']));
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
                            $user = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_id = :id");
                            ?>


                            <div class="form-group">
                                <label for="user_firstname">Vorname</label>
                                <input id="user_firstname" name="user_firstname" value="<?php echo $user['user_firstname']; ?>" class="form-control" placeholder="Vorname" type="text" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="user_lastname">Nachname</label>
                                <input id="user_lastname" name="user_lastname" value="<?php echo $user['user_lastname']; ?>" class="form-control" placeholder="Nachname" type="text" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="user_email">Email</label>
                                <input id="user_email" name="user_email" value="<?php echo $user['user_email']; ?>" class="form-control" placeholder="Email" type="email" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="user_secure_code">Sicherheitscode</label>
                                <input id="user_secure_code" name="user_secure_code" value="<?php echo $user['user_secure_code']; ?>" class="form-control" placeholder="Sicherheitscode" type="text" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="user_role_id">Rolle</label>
                                <select id="user_role_id" class="form-control" name="user_role_id">
                                    <?php
                                    $roles = $db->query("SELECT * FROM " . TABLE_ROLES);
                                    foreach ($roles as $role) {
                                        ?>
                                        <option value="<?php echo $role['role_id']; ?>"<?php echo ($role['role_id'] == $user['user_role_id']) ? ' selected="selected"' : ''; ?>><?php echo $role['role_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class = "form-group">
                                <label for = "user_active">aktiv?</label>
                                <select id = "user_active" class = "form-control" name = "user_active">
                                    <option value = "0"<?php echo ($user['user_active'] == 0) ? ' selected="selected"' : '';
                                    ?>>nein</option>
                                    <option value="1"<?php echo ($user['user_active'] == 1) ? ' selected="selected"' : ''; ?>>ja</option>
                                </select>
                            </div>
                            <button class="btn btn-primary" type="submit" name="edit">speichern</button>
                        </form>
                    </div>
                    <div class="col-md-7">
                        <h3>Räume zuweisen</h3>
                        <?php
                        //Prüfen ob es Räume gibt
                        $existsRooms = $db->row("SELECT room_id FROM " . TABLE_ROOMS, PDO::FETCH_NUM);
                        if ($existsRooms) {
                            //Raum hinzufügen
                            if (isset($_GET['removeroom'])) {
                                $update = json_decode($adminUserData->deleteRoomToUser($_GET, $_GET['edit'], $_GET['removeroom']));
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
                            <form method="POST" action="<?php echo BASEDIR; ?>admin/users/edit/<?php echo $_GET['edit']; ?>" role="form">
                                <?php
                                if (isset($_POST['addRoom'])) {
                                    $addRoom = json_decode($adminUserData->addRoomToUser($_POST, $_GET['edit']));
                                    if ($addRoom->status == 'error') {
                                        ?>
                                        <div class="alert alert-danger"><?php echo $addRoom->msg; ?></div>
                                        <?php
                                    }
                                    if ($addRoom->status == 'success') {
                                        ?>
                                        <div class="alert alert-success"><?php echo $addRoom->msg; ?></div>
                                        <?php
                                    }
                                }

                                $db->bind("id", $_GET['edit']);
                                $user = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_id = :id");
                                ?>
                                <div class="form-group">
                                    <label for="role_room_room_id">Raum</label>
                                    <select id="role_room_room_id" class="form-control" name="role_room_room_id">
                                        <?php
                                        $rooms = $db->query("SELECT * FROM " . TABLE_ROOMS);
                                        foreach ($rooms as $room) {
                                            ?>
                                            <option value="<?php echo $room['room_id']; ?>"><?php echo $room['room_name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="role_room_role_id">Rolle</label>
                                    <select id="role_room_role_id" class="form-control" name="role_room_role_id">
                                        <?php
                                        $roles = $db->query("SELECT * FROM " . TABLE_ROLES);
                                        foreach ($roles as $role) {
                                            ?>
                                            <option value="<?php echo $role['role_id']; ?>"><?php echo $role['role_name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit" name="addRoom">speichern</button>
                                </div>
                            </form>

                            <?php
                            //Benutzerräume auslesen
                            $userRooms = $db->query("SELECT * FROM " . TABLE_ROOMS . " LEFT JOIN " . TABLE_USER_ROOMS . " ON room_id = role_room_room_id LEFT JOIN  " . TABLE_USERS . " ON user_id = role_room_user_id LEFT JOIN  " . TABLE_ROLES . " ON role_id = role_room_role_id WHERE user_id = :user_id", array("user_id" => $user['user_id']));
                            if (!$userRooms) {
                                ?>
                                <div class="alert alert-info">Es existieren noch keine Räume für diesen Benutzer</div>
                                <?php
                            } else {
                                ?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Rolle</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($userRooms as $room) {
                                            ?>
                                            <tr>
                                                <td><?php echo $room['room_id']; ?></td>
                                                <td><?php echo $room['room_name']; ?></td>
                                                <td><?php echo $room['role_name']; ?></td>
                                                <td class="text-right">
                                                    <a href="<?php echo BASEDIR; ?>admin/users/edit/<?php echo $_GET['edit']; ?>/room/remove/<?php echo $room['room_id']; ?>/<?php echo $userData->getToken(); ?>" class="btn btn-danger btn-xs delete-button">
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
                        } else {
                            ?>
                            <div class="alert alert-info">Es existieren noch keine Räume</div>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            <?php }
            ?>

            <div class="row">
                <div class="col-md-12">
                    <a href="<?php echo BASEDIR; ?>admin/users" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> zurück zur Benutzerübersicht
                    </a>
                </div>
            </div>


            <?php
        } else if (isset($_GET['create'])) {
            ?>
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <h1>Benutzer erstellen</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                   <div class="well">
                    <form method="POST" action="<?php echo BASEDIR; ?>admin/users/create" role="form">

                        <?php
                        $user_firstname = '';
                        $user_lastname = '';
                        $user_email = '';
                        $user_secure_code = '';
                        $user_role_id = '';
                        $user_active = '';

                        if (isset($_POST['create'])) {
                            $user_firstname = $_POST['user_firstname'];
                            $user_lastname = $_POST['user_lastname'];
                            $user_email = $_POST['user_email'];
                            $user_secure_code = $_POST['user_secure_code'];
                            $user_role_id = $_POST['user_role_id'];
                            $user_active = $_POST['user_active'];


                            $update = json_decode($userData->createUser($_POST, true));
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
                            <label for="user_firstname">Vorname</label>
                            <input id="user_firstname" name="user_firstname" value="<?php echo $user_firstname; ?>" class="form-control" placeholder="Vorname" type="text" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_lastname">Nachname</label>
                            <input id="user_lastname" name="user_lastname" value="<?php echo $user_lastname; ?>" class="form-control" placeholder="Nachname" type="text" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_email">Email</label>
                            <input id="user_email" name="user_email" value="<?php echo $user_email; ?>" class="form-control" placeholder="Email" type="email" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_secure_code">Sicherheitscode</label>
                            <input id="user_secure_code" name="user_secure_code" value="<?php echo $user_secure_code; ?>" class="form-control" placeholder="Sicherheitscode" type="text" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_password">Passwort</label>
                            <input id="user_password" name="user_password" class="form-control" placeholder="Passwort" type="password" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_password_repeat">Passwort wdhl.</label>
                            <input id="user_password_repeat" name="user_password_repeat" class="form-control" placeholder="Passwort wdhl." type="password" required autofocus>
                        </div>   
                        <div class="form-group">
                            <label for="user_role_id">Rolle</label>
                            <select id="user_role_id" class="form-control" name="user_role_id">
                                <?php
                                $roles = $db->query("SELECT * FROM " . TABLE_ROLES);
                                foreach ($roles as $role) {
                                    ?>
                                    <option value="<?php echo $role['role_id']; ?>"<?php echo ($role['role_id'] == $user_role_id) ? ' selected="selected"' : ''; ?>><?php echo $role['role_name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class = "form-group">
                            <label for = "user_active">aktiv?</label>
                            <select id = "user_active" class = "form-control" name = "user_active">
                                <option value = "0"<?php echo ($user_active == 0) ? ' selected="selected"' : '';
                                ?>>nein</option>
                                <option value="1"<?php echo ($user_active == 1) ? ' selected="selected"' : ''; ?>>ja</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit" name="create">hinzufügen</button>
                    </form>

                    <br />
                    <a href="<?php echo BASEDIR; ?>admin/users" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> zurück zur Benutzerübersicht
                    </a>
                    </div>
                </div>
            </div>
            <?php
        } else {
            $userList = $db->query("SELECT * FROM " . TABLE_USERS);
            ?>
            <div class="row">
                <div class="col-md-12">
                    <h1>Benutzer</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                       <div class="well">
                        <?php
                        if (!$userList) {
                            ?>
                            <div class="alert alert-info">Es wurden noch keine Benutzer erstellt.</div>
                            <?php
                        } else {
                            ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Benutzername</th>
                                        <th>Person</th>
                                        <th>Email</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($userList as $user) {
                                        ?>

                                        <tr>
                                            <td><?php echo $user['user_firstname']; ?> <?php echo $user['user_lastname']; ?></td>
                                            <td><?php echo $user['user_email']; ?></td>
                                            <td class="text-right">
                                                <a href="<?php echo BASEDIR; ?>admin/users/edit/<?php echo $user['user_id']; ?>" class="btn btn-default btn-xs">
                                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                </a>
                                                <a href="<?php echo BASEDIR; ?>admin/users/remove/<?php echo $user['user_id']; ?>" class="btn btn-danger btn-xs delete-button">
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
                        <a href="<?php echo BASEDIR; ?>admin/users/create" class="btn btn-success">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Benutzer erstellen
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
