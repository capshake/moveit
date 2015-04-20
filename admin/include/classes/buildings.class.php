<?php

class Buildings extends Token {
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
    public function createBuilding($data = '') {

        global $db;

        $return = array("status" => "", "msg" => "");

        if (isset($data) && !empty($data)) {
            $existsBuilding = $db->row("SELECT * FROM " . TABLE_BUILDINGS . " WHERE building_name = :building_name", array("building_name" => $data['building_name']), PDO::FETCH_NUM);
            $building_name = trim($data['building_name']) == true;

            //Überprüfung der einzelnen Felder
            if ($existsBuilding) {
                $return['status'] = 'error';
                $return['msg'] = 'Ein Gebäude mit diesem Namen existiert bereits.';
            }
            if (empty($building_name)) {
                $return['status'] = 'error';
                $return['msg'] = 'Füllen Sie bitte das Feld Gebäudename aus.';
            }
            if (empty($data['building_type'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Füllen Sie bitte das Feld Gebäudetyp aus.';
            }

            if (!$this->isValidToken(@$data['token'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }
            $this->newToken();


            //Wenn kein Fehler passiert ist wird der Benutzer in die Datenbank geschrieben
            if ($return['status'] != 'error') {
                $insert = $db->query("INSERT INTO " . TABLE_BUILDINGS . " (building_name, building_type) "
                        . "VALUES(:building_name, :building_type)", array(
                    "building_name" => $data['building_name'],
                    "building_type" => $data['building_type']
                ));

                if ($insert > 0) {
                    $return['status'] = 'success';
                    $return['msg'] = 'Das Gebäude wurde erstellt.';
                }
            }
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es wurden keine Daten übertragen.';
        }
        return json_encode($return);
    }

    /**
     * Gebäude bearbeiten
     * @global type $db
     * @param type $data
     * @return type
     */
    public function updateBuilding($data = '', $id = 0) {

        global $db;

        $return = array("status" => "", "msg" => "");

        if (isset($data) && !empty($data) && !empty($id)) {
            $existsBuilding = $db->row("SELECT * FROM " . TABLE_BUILDINGS . " WHERE building_id = :building_id", array("building_id" => $id), PDO::FETCH_NUM);
            $existsBuildingName = $db->row("SELECT * FROM " . TABLE_BUILDINGS . " WHERE building_name = :building_name AND building_id != :building_id",
                                           array("building_name" => $data['building_name'], "building_id" => $id), PDO::FETCH_NUM);
            $building_name = trim($data['building_name']) == true;

            //Überprüfung der einzelnen Felder
            if (!$existsBuilding) {
                $return['status'] = 'error';
                $return['msg'] = 'Dieses Gebäude existiert nicht.';
            }
            if ($existsBuildingName) {
                $return['status'] = 'error';
                $return['msg'] = 'Ein Gebäude mit diesem Namen existiert bereits.';
            }
            if (empty($building_name)) {
                $return['status'] = 'error';
                $return['msg'] = 'Füllen Sie bitte das Feld Gebäudename aus.';
            }
            /*if (empty($data['building_type'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Füllen Sie bitte das Feld Gebäudetyp aus.';
            }*/


            if (!$this->isValidToken(@$data['token'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }
            $this->newToken();


            //Wenn kein Fehler passiert ist wird der Benutzer in die Datenbank geschrieben
            if ($return['status'] != 'error') {

                $update = $db->query("UPDATE " . TABLE_BUILDINGS . " SET building_name = :building_name WHERE building_id = :building_id", array(
                    "building_name" => $data['building_name'],
                    //"building_type" => $data['building_type'],
                    "building_id" => $id
                ));


                $return['status'] = 'success';
                $return['msg'] = 'Das Gebäude wurde bearbeitet.';
            }
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es wurden keine Daten übertragen.';
        }
        return json_encode($return);
    }

}
