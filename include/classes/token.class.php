<?php

class Token {

    private $token = '';

    /**
     * neuen Token erstellen
     * @return boolean
     */
    public function newToken() {
        $_SESSION['csrf_token'] = $this->generateToken();
        $this->token = $_SESSION['csrf_token'];

        return true;
    }

    /**
     * CSRF Token generieren
     * @return type
     */
    private function generateToken() {
        return sha1(md5(uniqid() . uniqid() . mt_rand()));
    }

    /**
     * Gibt Token zurück
     * @return type
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * Token auf Gültigkeit überprüfen
     * @param type $token
     * @return boolean
     */
    public function isValidToken($token) {
        if (!isset($token) || @$token !== @$_SESSION['csrf_token']) {
            $this->unsetToken();
            $this->newToken();
            return false;
        }
        $this->unsetToken();
        return true;
    }

    /**
     * Token ungültig machen
     * @return boolean
     */
    public function unsetToken() {
        unset($_SESSION['csrf_token']);
        return true;
    }

}
