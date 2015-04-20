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
            $room_name = trim($data['room_name']) == true;

            //Überprüfung der einzelnen Felder
            if ($existsBuilding) {
                $return['status'] = 'error';
                $return['msg'] = 'Ein Raum mit diesem Namen existiert bereits.';
            }
            if (empty($room_name)) {
                $return['status'] = 'error';
                $return['msg'] = 'Füllen Sie bitte das Feld Raumname aus.';
            }
            /*if (empty($data['room_type'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Füllen Sie bitte das Feld Raumtyp aus.';
            }*/
            if (empty($data['room_map_id'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie bitte eine Map an.';
            }

            if (!$this->isValidToken(@$data['token'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }
            $this->newToken();


            //Wenn kein Fehler passiert ist wird der Raum in die Datenbank geschrieben
            if ($return['status'] != 'error') {
                $db -> bind("map_id", $data['room_map_id']);
                $type = $db -> single("SELECT building_type FROM buildings, maps WHERE map_building_id = building_id AND map_id = :map_id");

                if(!isset($data['room_name_alt'])){
                    $insert = $db->query("INSERT INTO " . TABLE_ROOMS . " (room_name, room_type, room_map_id) "
                        . "VALUES(:room_name, :room_type, :room_map_id)", array(
                    "room_name" => $data['room_name'],
                    "room_type" => $type,
                    "room_map_id" => $data['room_map_id']
                    ));
                }else{
                    $insert = $db->query("INSERT INTO " . TABLE_ROOMS . " (room_name, room_name_alt, room_type, room_map_id) "
                        . "VALUES(:room_name, :room_name_alt, :room_type, :room_map_id)", array(
                    "room_name" => $data['room_name'],
                    "room_name_alt" => $data['room_name_alt'],
                    "room_type" => $type,
                    "room_map_id" => $data['room_map_id']
                    ));
                }



                if ($insert > 0) {
                    $return['status'] = 'success';
                    $return['msg'] = 'Der Raum wurde erstellt.';
                }
            }
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es wurden keine Daten übertragen.';
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
            $room_name = trim($data['room_name']) == true;

            //Überprüfung der einzelnen Felder
            if (!$existsBuilding) {
                $return['status'] = 'error';
                $return['msg'] = 'Dieser Raum existiert nicht.';
            }
            if ($existsBuildingName) {
                $return['status'] = 'error';
                $return['msg'] = 'Ein Raum mit diesem Namen existiert bereits.';
            }
            if (empty($room_name)) {
                $return['status'] = 'error';
                $return['msg'] = 'Füllen Sie bitte das Feld Raumname aus.';
            }
            /*if (empty($data['room_type'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Füllen Sie bitte das Feld Raumtyp aus.';
            }*/
            /*if (empty($data['room_map_id'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie bitte eine Map an.';
            }*/

            if (!$this->isValidToken(@$data['token'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }
            $this->newToken();


            //Wenn kein Fehler passiert ist wird in der Datenbank ein Update des Raumes ausgeführt
            if ($return['status'] != 'error') {

                $db->query("UPDATE " . TABLE_ROOMS . " SET room_name = :room_name WHERE room_id = :room_id", array(
                    "room_name" => $data['room_name'],
                    "room_id" => $id
                ));

                if(isset($data['room_name_alt'])){
                    $db->query("UPDATE " . TABLE_ROOMS . " SET room_name_alt = :room_name_alt WHERE room_id = :room_id", array(
                        "room_name_alt" => $data['room_name_alt'],
                        "room_id" => $id
                    ));
                }

                $return['status'] = 'success';
                $return['msg'] = 'Der Raum wurde bearbeitet.';
            }
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es wurden keine Daten übertragen.';
        }
        return json_encode($return);
    }

}
