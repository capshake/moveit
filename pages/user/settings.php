<?php
include_once '../../include/config.php';
include_once '../../include/classes/validation.class.php';

if (!$userData->isLoggedIn()) {
    header('location: ' . BASEDIR . 'pages/main/login.php');
} else {
    include_once '../../include/header.php';
    include_once '../../include/menu.php';
    ?>
<div class="container">

    <div class="row">
        <div class="col-md-offset-2 col-md-4">
            <h2>Profil bearbeiten</h2>
        </div>
    </div>

        <form method="POST" action="<?php echo BASEDIR; ?>settings" role="form">

            <?php if (isset($_POST[ 'edit'])) { 
			
				$update = json_decode('{ "status" : "", "msg" : ""}');
				
				// eMail Überprüfung
				if($userData->getEmail() != $_POST['user_email']) { 
					// eMail geändert: Überprüfen ob eMail schon vergeben ist
					$emailUnique = json_decode(Validation::validateIfEmailIsUnique($_POST['user_email']));
					if( $emailUnique->status == 'error') { // wenn sie bereits vergeben ist
						$update->status = 'error';
						$update->msg = $emailUnique->msg;
					}
				}
				
				// altes Passwort Überprüfung - falls Feld nicht leer
				if(!empty($_POST['user_old_password'])){
					$userFromDb = $db->row("SELECT * FROM ". TABLE_USERS . " WHERE user_email = :email", array("email" => $_POST['user_email']));
					if ($userData->makePasswordHash($_POST['user_old_password']) != $userFromDb['user_password']) {
						$update->status = 'error';
						$update->msg = 'Das alte Passwort ist nicht korrekt.';
					}
				}
				
				// Felder für neues Passwort auf Gleichheit überprüfen - falls eins der Felder nicht leer
				// und prüfen, ob altes Passwort eingegeben wurde
				if(!empty($_POST['user_new_password']) || !empty($_POST['user_repeat_new_password'])){
					if ($_POST['user_new_password'] != $_POST['user_repeat_new_password']) {
						$update->status = 'error';
						$update->msg = 'Die Eingaben für das neue Passwort stimmen nicht überein.';
					} else if (empty($_POST['user_old_password'])) {
						$update->status = 'error';
						$update->msg = 'Für die Änderung des Passworts muss auch das alte Passwort eingegeben werden.';
					}
				}
				
				// Wenn 'status' nicht auf 'error' gesetzt wurde -> Benutzer updaten
				if ($update->status != 'error') {
					$update = json_decode($userData->updateUser($_POST));
				}
				
				if ($update->status == 'error') { ?>
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<div class="alert alert-danger"><?php echo $update->msg; ?></div>
						</div>
					</div>
				<?php } 
				if ($update->status == 'success') { ?>
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<div class="alert alert-success"><?php echo $update->msg; ?></div>
						</div>
					</div>
				<?php 
				}
			} 
			$db->bind("id", $userData->getUserId()); 
			$user = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_id = :id"); ?>
            
			<div class="col-md-4 col-md-offset-2">
	        	<div class="panel panel-default">
					<div class="panel-heading">Benutzerangaben bearbeiten</div>
					<div class="panel-body">
	                   
                        <div class="form-group">
                            <label for="user_firstname">Vorname</label>
                            <input id="user_firstname" name="user_firstname" value="<?php echo $user['user_firstname']; ?>" class="first form-control" placeholder="Vorname" type="text" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_lastname">Nachname</label>
                            <input id="user_lastname" name="user_lastname" value="<?php echo $user['user_lastname']; ?>" class="first form-control" placeholder="Nachname" type="text" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_email">Email</label>
                            <input id="user_email" name="user_email" value="<?php echo $user['user_email']; ?>" class="first form-control" placeholder="Email" type="email" required autofocus>
                        </div>                 
			      	</div>
				</div>
			</div>

			<div class="col-md-4">
            	<div class="panel panel-default">
					<div class="panel-heading">Passwort ändern</div>
					<div class="panel-body">
                        <div class="form-group">
                            <label for="user_old_password">Altes Passwort</label>
                            <input id="user_old_password" name="user_old_password" value="" class="first form-control" placeholder="" type="password" autofocus>
                        </div>
						<div class="form-group">
                            <label for="user_new_password">Neues Passwort</label>
                            <input id="user_new_password" name="user_new_password" value="" class="first form-control" placeholder="" type="password" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="user_lastname">Neues Passwort wiederholen</label>
                            <input id="user_repeat_new_password" name="user_repeat_new_password" value="" class="first form-control" placeholder="" type="password" autofocus>
                        </div>
		            </div>
		      	</div>
		     </div>

            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <button class="btn btn-primary btn-block col-md1-12" type="submit" name="edit" id="save_settings">Speichern</button>
                </div>
            </div>
        </form>
    </div>

    <?php
    include_once '../../include/footer.php';
}
