<?php

class Rooms extends Token {
    /**
     * Konstruktor
     */
    public function __construct() {

    }
    /**
     * Raum erstellen
     * @global type $db
     * @param type $data
     * @return type
     */
    public function createRoom($data = '') {

        global $db;

        $return = array("status" => "", "msg" => "");

        if (isset($data) && !empty($data)) {
            $existsBuilding = $db->row("SELECT * FROM " . TABLE_ROOMS . " WHERE room_name = :room_name", array("room_name" => $data['room_name']), PDO::FETCH_NUM);

            //Überprüfung der einzelnen Felder
            if ($existsBuilding) {
                $return['status'] = 'error';
                $return['msg'] = 'Ein aum mit diesem Namen existiert bereits';
            }
            if (empty(trim($data['room_name']))) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie einen Namen an';
            }


            if (!$this->isValidToken(@$data['token'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }
            $this->newToken();


            //Wenn kein Fehler passiert ist wird der Benutzer in die Datenbank geschrieben
            if ($return['status'] != 'error') {
                $insert = $db->query("INSERT INTO " . TABLE_ROOMS . " (room_name) "
                        . "VALUES(:room_name)", array(
                    "room_name" => $data['room_name']
                ));

                if ($insert > 0) {
                    $return['status'] = 'success';
                    $return['msg'] = 'Der Raum wurde erfolgreich angelegt';
                }
            }
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es wurden keine Daten übertragen';
        }
        return json_encode($return);
    }

    /**
     * Raum bearbeiten
     * @global type $db
     * @param type $data
     * @return type
     */
    public function updateRoom($data = '', $id = 0) {

        global $db;

        $return = array("status" => "", "msg" => "");

        if (isset($data) && !empty($data) && !empty($id)) {
            $existsBuilding = $db->row("SELECT * FROM " . TABLE_ROOMS . " WHERE room_id = :room_id", array("room_id" => $id), PDO::FETCH_NUM);
            $existsBuildingName = $db->row("SELECT * FROM " . TABLE_ROOMS . " WHERE room_name = :room_name AND room_id != :room_id", array("room_name" => $data['room_name'], "room_id" => $id), PDO::FETCH_NUM);

            //Überprüfung der einzelnen Felder
            if (!$existsBuilding) {
                $return['status'] = 'error';
                $return['msg'] = 'Dieser Raum existiert nicht';
            }
            if ($existsBuildingName) {
                $return['status'] = 'error';
                $return['msg'] = 'Ein Raum mit diesem Namen existiert bereits';
            }
            if (empty(trim($data['room_name']))) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie einen Namen an';
            }


            if (!$this->isValidToken(@$data['token'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }
            $this->newToken();


            //Wenn kein Fehler passiert ist wird der Benutzer in die Datenbank geschrieben
            if ($return['status'] != 'error') {

                $update = $db->query("UPDATE " . TABLE_ROOMS . " SET room_name = :room_name WHERE room_id = :room_id", array(
                    "room_name" => $data['room_name'],
                    "room_id" => $id
                ));


                $return['status'] = 'success';
                $return['msg'] = 'Der Raum wurde bearbeitet';
            }
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es wurden keine Daten übertragen';
        }
        return json_encode($return);
    }

}
