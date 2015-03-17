<?php

class Users extends Token {

    /**
     * Konstruktor
     */
    public function __construct() {
        
    }

    /**
     * Gebäude erstellen
     * @global type $db
     * @param type $data
     * @return type
     */
    public function addRoomToUser($data = '', $id = 0) {

        global $db;

        $return = array("status" => "", "msg" => "");

        if (isset($data) && !empty($data)) {

            $existsRoomUser = $db->row("SELECT * FROM " . TABLE_USER_ROOMS . " WHERE role_room_user_id = :role_room_user_id AND role_room_room_id = :role_room_room_id", array(
                    "role_room_user_id" => $id,
                    "role_room_room_id" => $data['role_room_room_id']
                ), PDO::FETCH_NUM);
            
            if($existsRoomUser) {
                $return['status'] = 'error';
                $return['msg'] = 'Der Benutzer ist diesem Raum schon zugewiesen.';
            }
            //Überprüfung der einzelnen Felder
            if (!$this->isValidToken(@$data['token'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }
            $this->newToken();

            //Wenn kein Fehler passiert ist wird der Benutzer in die Datenbank geschrieben
            if ($return['status'] != 'error') {
                $insert = $db->query("INSERT INTO " . TABLE_USER_ROOMS . " (role_room_user_id, role_room_room_id, role_room_role_id) "
                        . "VALUES(:role_room_user_id, :role_room_room_id, :role_room_role_id)", array(
                    "role_room_user_id" => $id,
                    "role_room_room_id" => $data['role_room_room_id'],
                    "role_room_role_id" => $data['role_room_role_id']
                ));

                if ($insert > 0) {
                    $return['status'] = 'success';
                    $return['msg'] = 'Der Raum wurde erfolgreich zugewiesen.';
                }
            }
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es wurden keine Daten übertragen.';
        }
        return json_encode($return);
    }

    /**
     * Raum zu Benutzer löschen
     * @global type $db
     * @param type $data
     * @param type $user_id
     * @param type $room_id
     * @return type
     */
    public function deleteRoomToUser($data, $user_id = 0, $room_id = 0) {

        global $db;

        $return = array("status" => "", "msg" => "");

        if (isset($data) && !empty($data)) {

            $existsRoomUser = $db->row("SELECT * FROM " . TABLE_USER_ROOMS . " WHERE role_room_user_id = :role_room_user_id AND role_room_room_id = :role_room_room_id", array(
                    "role_room_user_id" => $user_id,
                    "role_room_room_id" => $room_id
                ), PDO::FETCH_NUM);
            
            if(!$existsRoomUser) {
                $return['status'] = 'error';
                $return['msg'] = 'Der Raum für diesen Benutzer existiert nicht.';
            }
            //Überprüfung der einzelnen Felder
            if (!$this->isValidToken(@$data['token'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }
            $this->newToken();

            //Wenn kein Fehler passiert ist wird die Verbindung vom Benutzer zum Raum aus der Datenbank gelöscht
            if ($return['status'] != 'error') {
                $delete = $db->query("DELETE FROM " . TABLE_USER_ROOMS . " WHERE role_room_room_id = :role_room_room_id AND role_room_user_id = :role_room_user_id ", array(
                    "role_room_user_id" => $user_id,
                    "role_room_room_id" => $room_id
                ));

                if ($delete > 0) {
                    $return['status'] = 'success';
                    $return['msg'] = 'Der Raum wurde erfolgreich gelöscht.';
                }
            }
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es wurden keine Daten übertragen.';
        }
        return json_encode($return);
    }

}
