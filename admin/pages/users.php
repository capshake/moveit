<?php
include_once '../../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    $headerTitle = 'Adminpanel - Benutzer';

    include_once '../../include/header.php';
    include_once '../include/menu.php';
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
                <div class="col-md-offset-4 col-md-4">
                    <h1>Benutzer bearbeiten</h1><br />
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <?php if (!$existsUser) { ?>
                        <div class="alert alert-info">Der Benutzer existiert nicht!</div>
                    <?php } else { ?>
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
                                <label for="user_name">Benutzername</label>
                                <input id="user_name" name="user_name" value="<?php echo $user['user_name']; ?>" class="form-control" placeholder="Benutzername" type="text" required autofocus>
                            </div>
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
                                <input id="user_email" name="user_email" value="<?php echo $user['user_email']; ?>" class="form-control" placeholder="Email" type="text" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="user_secure_code">Sicherheistcode</label>
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

                    <?php } ?>
                    <br />

                    <a href="<?php echo BASEDIR; ?>admin/users" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> zurück zur Benutzerübersicht
                    </a>
                </div>
            </div>
            <?php
        } else if (isset($_GET['create'])) {
            ?>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <h1>Benutzer erstellen</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <form method="POST" action="<?php echo BASEDIR; ?>admin/users/create" role="form">

                        <?php
                        $user_name = '';
                        $user_firstname = '';
                        $user_lastname = '';
                        $user_email = '';
                        $user_secure_code = '';
                        $user_role_id = '';
                        $user_active = '';

                        if (isset($_POST['create'])) {
                            $user_name = $_POST['user_name'];
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
                            <label for="user_name">Benutzername</label>
                            <input id="user_name" name="user_name" value="<?php echo $user_name; ?>" class="form-control" placeholder="Benutzername" type="text" required autofocus>
                        </div>
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
                            <input id="user_email" name="user_email" value="<?php echo $user_email; ?>" class="form-control" placeholder="Email" type="text" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_secure_code">Sicherheistcode</label>
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

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
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
                                    <th scope="row"><?php echo $user['user_id']; ?></th>
                                    <td><?php echo $user['user_name']; ?></td>
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
                    <a href="<?php echo BASEDIR; ?>admin/users/create" class="btn btn-success">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Benutzer erstellen
                    </a>
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
