<?php

include_once 'token.class.php';

class User extends Token {

    //Variablen
    private $userId = '';
    private $userName = '';
    private $firstName = '';
    private $lastName = '';
    private $email = '';
    private $role = '';
    public $csrfToken = '';

    /**
     * Konstruktor
     */
    public function __construct() {
        $this->csrfToken = new Token();

        if ($this->isloggedIn()) {
            $this->userId = $_SESSION['user_id'];
            $this->userName = $_SESSION['user_name'];
            $this->firstName = $_SESSION['user_firstname'];
            $this->lastName = $_SESSION['user_lastname'];
            $this->email = $_SESSION['user_email'];
            $this->role = $_SESSION['user_role_id'];
        }
        if (!isset($_POST['token']) && !isset($_GET['token'])) {
            $this->newToken();
        }
    }

    /**
     * UserId des Benutzer bekommen
     * @return type
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Username des Benutzers bekommen
     * @return type
     */
    public function getUserName() {
        return $this->userName;
    }

    /**
     * Vorname des Benutzers bekommen
     * @return type
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Nachname des Benutzers bekommen
     * @return type
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Email des Benutzers bekommen
     * @return type
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Role des Benutzers bekommen
     * @return type
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Einloggen eines Benutzers
     * @global type $db
     * @param type $email
     * @param type $password
     * @return boolean
     */
    public function login($email = '', $password = '') {
        global $db;

        if (!empty($email) && !empty($password)) {
            $db->bind("email", $email);
            $user = $db->row("SELECT * FROM users WHERE user_email = :email AND user_active = 1 AND user_role_id != 0");


            if ($user['user_id'] && $this->makePasswordHash($password) == $user['user_password'] && $user['user_active'] == 1) {

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_firstname'] = $user['user_firstname'];
                $_SESSION['user_lastname'] = $user['user_lastname'];
                $_SESSION['user_email'] = $user['user_email'];
                $_SESSION['user_role_id'] = $user['user_role_id'];

                return true;
            }
            return false;
        }
    }

    /**
     * Ausloggen des Benutzers
     * @return boolean
     */
    public function logout($token = '') {
        if ($this->isValidToken($token)) {
            session_unset();
            session_destroy();

            return true;
        }
        return false;
    }

    /**
     * Passwort generieren
     * @return type
     */
    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    /**
     * Passwort mit Salt aus config hashen
     * @param type $password
     * @return type
     */
    private function makePasswordHash($password) {
        return crypt($password, SALT);
    }

    /**
     * Prüfen ob Benutzer eingeloggt
     * @return boolean
     */
    public function isloggedIn() {
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }

    /**
     * Prüfen ob Benutzer ist Admin
     * @return boolean
     */
    public function isAdmin() {
        if ($this->role == 2) {
            return true;
        }
        return false;
    }

    /**
     * Benutzer erstellen
     * @global type $db
     * @param type $data
     * @return type
     */
    public function createUser($data = '') {

        global $db;

        $return = array("status" => "success", "msg" => "Es ist ein Fehler beim anlegen aufgetreten. Kontaktieren Sie einen Administrator");

        if (isset($data) && !empty($data)) {
            $existsEmail = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_email = :user_email", array("user_email" => $data['user_email']), PDO::FETCH_NUM);
            $existsUser = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_name = :user_name", array("user_name" => $data['user_name']), PDO::FETCH_NUM);

            //Überprüfung der einzelnen Felder
            if ($existsEmail) {
                $return['status'] = 'error';
                $return['msg'] = 'Ein Benutzer mit dieser Email existiert schon';
            }
            if ($existsUser) {
                $return['status'] = 'error';
                $return['msg'] = 'Ein Benutzer mit diesem Namen existiert schon';
            }
            if ($data['user_password'] != $data['user_password_repeat']) {
                $return['status'] = 'error';
                $return['msg'] = 'Die eingebenen Passwörter stimmen nicht überein';
            }
            if (empty($data['user_name'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie einen Namen an';
            }
            if (empty($data['user_firstname'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie einen Vornamen an';
            }
            if (empty($data['user_lastname'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie einen Nachnamen an';
            }


            if ($this->isValidToken(@$_POST['token'])) {
                $this->newToken();
            } else {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }


            //Wenn kein Fehler passiert ist wird der Benutzer in die Datenbank geschrieben
            if ($return['status'] != 'error') {
                $userSecureCode = $this->makePasswordHash(time() . $data['user_email']);

                $insert = $db->query("INSERT INTO " . TABLE_USERS . " (user_name, user_firstname, user_lastname, user_password, user_email, user_role_id, user_active, user_secure_code) "
                        . "VALUES(:user_name, :user_firstname, :user_lastname, :user_password, :user_email, :user_role_id, :user_active, :user_secure_code)", array(
                    "user_name" => $data['user_name'],
                    "user_firstname" => $data['user_firstname'],
                    "user_lastname" => $data['user_lastname'],
                    "user_password" => $this->makePasswordHash($data['user_password']),
                    "user_email" => $data['user_email'],
                    "user_role_id" => $data['user_role_id'],
                    "user_active" => $data['user_active'],
                    "user_secure_code" => $userSecureCode
                ));


                echo print_r($insert);

                if ($insert > 0) {
                    //Email wird versendet
                    $text = "Hallo " . $data['user_firstname'] . " " . $data['user_lastname'] . ",\n\nWillkommen bei moveIT damit Sie unseren Service benutzen können, müssen Sie zuerst Ihren Account über diesen Link aktivieren\n\n" . BASEURL . BASEDIR . "login?code=" . $userSecureCode . "\n\nMit freundlichen Grüßen,\nDas moveIT-Team";
                    mail($data['user_email'], 'Registrierung bei moveIT', $text, 'from: noreply@moveit.com');


                    $return['status'] = 'success';
                    $return['msg'] = 'Der Benutzer wurde erfolgreich angelegt';
                }
            }
        }
        return json_encode($return);
    }

    /**
     * Benutzer bearbeiten
     * @global type $db
     * @param type $data
     * @return type
     */
    public function updateUser($data = '') {

        global $db;


        $return = array("status" => "success", "msg" => "Es ist ein Fehler aufgetreten. Kontaktieren Sie einen Administrator");

        if (isset($data) && !empty($data)) {
            $existsUser = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_id = :user_id", array("user_id" => $this->userId), PDO::FETCH_NUM);
            $existsUserName = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_name = :user_name AND user_id != :user_id", array("user_name" => $data['user_name'], "user_id" => $this->userId), PDO::FETCH_NUM);
            
            //Überprüfung der einzelnen Felder
            if($existsUserName) {
                $return['status'] = 'error';
                $return['msg'] = 'Der Benutzername ist schon vergeben';
            }
            if (!$existsUser) {
                $return['status'] = 'error';
                $return['msg'] = 'Der Benutzer existiert nicht';
            }
            if (empty($data['user_name'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie einen Namen an';
            }
            if (empty($data['user_firstname'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie einen Vornamen an';
            }
            if (empty($data['user_lastname'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie einen Nachnamen an';
            }


            if ($this->isValidToken(@$_POST['token'])) {
                $this->newToken();
            } else {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }

            //Wenn kein Fehler passiert ist wird der Benutzer in die Datenbank geschrieben
            if ($return['status'] != 'error') {
                
                $update = $db->query("UPDATE " . TABLE_USERS . " SET "
                        . "user_name = :user_name, "
                        . "user_firstname = :user_firstname, "
                        . "user_lastname = :user_lastname, "
                        . "user_email = :user_email WHERE user_id = :user_id", array(
                    "user_name" => $data['user_name'],
                    "user_firstname" => $data['user_firstname'],
                    "user_lastname" => $data['user_lastname'],
                    "user_email" => $data['user_email'],
                    "user_id" => $this->userId
                ));

                $_SESSION['user_name'] = $data['user_name'];
                $_SESSION['user_firstname'] = $data['user_firstname'];
                $_SESSION['user_lastname'] = $data['user_lastname'];
                $_SESSION['user_email'] = $data['user_email'];
                
                
                $return['status'] = 'success';
                $return['msg'] = 'Der Benutzer wurde bearbeitet';
            }
        }
        return json_encode($return);
    }
    
    /**
     * Überprüfen des Aktivierungscodes
     * @global type $db
     * @param type $code
     * @return boolean
     */
    public function checkCreateUserCode($code) {

        global $db;

        //Prüfen ob ein Benutzer mit dem eingebenen Code existiert
        $update = $db->query("UPDATE " . TABLE_USERS . " SET user_active = :user_active WHERE user_secure_code = :user_secure_code", array("user_active" => "1", "user_secure_code" => $code));
        if ($update > 0) {
            return true;
        }
        return false;
    }

    /**
     * Passwort eines Benutzers zurücksetzen
     * @global type $db
     * @param type $email
     * @return boolean
     */
    public function resetUserPassword($email) {

        global $db;

        $return = array("status" => "", "msg" => "");


        if ($this->isValidToken(@$_POST['token'])) {
            $this->newToken();
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Token abgelaufen.';
        }

        if ($return['status'] != 'error') {
            //Benutzer der Email abrufen
            $user = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_email = :user_email AND user_active = 1 AND user_role_id != 0", array("user_email" => $email));

            if ($user['user_id']) {
                $newPassword = $this->randomPassword();

                //Prüfen ob ein Benutzer mit dem eingebenen Code existiert
                $update = $db->query("UPDATE " . TABLE_USERS . " SET user_password = :user_password WHERE user_email = :user_email AND user_active = 1", array("user_password" => $this->makePasswordHash($newPassword), "user_email" => $user['user_email']));
                if ($update > 0) {
                    //Email wird versendet
                    $text = "Hallo " . $user['user_firstname'] . " " . $user['user_lastname'] . ",\n\nIhr Passwort wurde zurückgesetzt.\n\nNeues Passwort:" . $newPassword . "\n" . BASEURL . BASEDIR . "login\n\nMit freundlichen Grüßen,\nDas moveIT-Team";
                    mail($user['user_email'], 'Registrierung bei moveIT', $text, 'from: noreply@moveit.com');

                    $return['status'] = 'success';
                    $return['msg'] = 'Ein neues Passwort wurde Ihnen zugesendet.';
                }
            } else {
                $return['status'] = 'error';
                $return['msg'] = 'Der Benutzer existiert nicht.';
            }
        }
        return json_encode($return);
    }

}
