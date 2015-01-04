<?php

class User {

    //Variablen
    private $userId     = '';
    private $userName   = '';
    private $firstName  = '';
    private $lastName   = '';
    private $email      = '';
    private $role       = '';

    /**
     * Konstruktor
     */
    public function __construct() {
        if ($this->isloggedIn()) {
            $this->userId       = $_SESSION['user_id'];
            $this->userName     = $_SESSION['user_name'];
            $this->firstName    = $_SESSION['user_firstname'];
            $this->lastName     = $_SESSION['user_lastname'];
            $this->email        = $_SESSION['user_email'];
            $this->role         = $_SESSION['user_role_id'];
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
            $user = $db->row("SELECT * FROM users WHERE user_email = :email");


            if ($user['user_id'] && $this->makePasswordHash($password) == $user['user_password']) {

                $_SESSION['user_id']        = $user['user_id'];
                $_SESSION['user_name']      = $user['user_name'];
                $_SESSION['user_firstname'] = $user['user_firstname'];
                $_SESSION['user_lastname']  = $user['user_lastname'];
                $_SESSION['user_email']     = $user['user_email'];
                $_SESSION['user_role_id']   = $user['user_role_id'];

                return true;
            }
            return false;
        }
    }

    /**
     * Ausloggen des Benutzers
     * @return boolean
     */
    public function logout() {
        session_unset();
        session_destroy();

        return true;
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

}
