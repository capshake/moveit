<?php

class mapEditor extends Token {

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
    public function createMap($data = '', $files = '') {

        global $db;

        $return = array("status" => "", "msg" => "");

        $existsBuilding = $db->row("SELECT * FROM " . TABLE_MAPS . " WHERE map_building_id = :map_building_id AND map_floor = :map_floor", array("map_building_id" => $data['map_building_id'], "map_floor" => $data['map_floor']), PDO::FETCH_NUM);

        //Überprüfung der einzelnen Felder
        if ($existsBuilding) {
            $return['status'] = 'error';
            $return['msg'] = 'Diese Map existiert bereits.';
        }
        if (empty($data['map_building_id'])) {
            $return['status'] = 'error';
            $return['msg'] = 'Geben Sie ein Gebäude an.';
        }
        if (!isset($data['map_floor'])) {
            $return['status'] = 'error';
            $return['msg'] = 'Geben Sie eine Etage an.';
        }
        if (!$this->isValidToken(@$data['token'])) {
            $return['status'] = 'error';
            $return['msg'] = 'Token abgelaufen.';
        }
        $this->newToken();

        //Wenn kein Fehler passiert ist wird die Map in die Datenbank geschrieben
        if ($return['status'] != 'error') {
            $insert = $db->query("INSERT INTO " . TABLE_MAPS . " (map_building_id, map_floor) "
                    . "VALUES(:map_building_id, :map_floor)", array(
                "map_building_id" => $data['map_building_id'],
                "map_floor" => $data['map_floor']
            ));

            if ($insert > 0) {
                $return['status'] = 'success';
                $return['msg'] = 'Die Map wurde erstellt';
            }
        }

        return json_encode($return);
    }

    /**
     * Map bearbeiten
     * @global type $db
     * @param type $data
     * @return type
     */
    public function updateMap($data = '', $files = '', $id = 0) {

        global $db;

        $return = array("status" => "", "msg" => "");

        if (isset($data) && !empty($data) && !empty($id)) {
            $map = $db->row("SELECT * FROM " . TABLE_MAPS . " WHERE map_id = :map_id", array("map_id" => $id));
            $existsMapBuildingFloor = $db->row("SELECT * FROM " . TABLE_MAPS . " WHERE map_building_id = :map_building_id AND map_floor = :map_floor AND map_id != :map_id", array("map_building_id" => $data['map_building_id'], "map_floor" => $data['map_floor'], "map_id" => $id), PDO::FETCH_NUM);

            //Überprüfung der einzelnen Felder
            if (!$map['map_id']) {
                $return['status'] = 'error';
                $return['msg'] = 'Diese Map existiert nicht.';
            }
            if ($existsMapBuildingFloor) {
                $return['status'] = 'error';
                $return['msg'] = 'Diese Map existiert bereits.';
            }
            if (empty($data['map_building_id'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie ein Gebäude an.';
            }
            if (!isset($data['map_floor'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie eine Etage an.';
            }
            if (!$this->isValidToken(@$data['token'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }

            if(isset($files['map_picture'])){
                $ex = end(explode('.', $files['map_picture']['name']));

                if (isset($files['map_picture']['name']) && !empty($files['map_picture']['name']) && !in_array(strtolower($ex), array('jpeg', 'jpg', 'gif', 'png'))) {
                    $return['status'] = 'error';
                    $return['msg'] = 'Nur Bilder im jpeg, png oder gif Format.';
                }
            }

            $this->newToken();



            //Wenn kein Fehler passiert ist wird der Benutzer in die Datenbank geschrieben
            if ($return['status'] != 'error') {

                // Wenn Uploadverzeichnis nicht existiert, erstellen
                if(!file_exists('../../uploads/maps')){
                        mkdir('../../uploads/maps');
                }

                $path = $map['map_picture'];
                if (isset($files['map_picture']['name']) && !empty($files['map_picture']['name'])) {
                    $path = 'uploads/maps/map_' . time() . '.' . $ex;

                    if (!move_uploaded_file($files['map_picture']['tmp_name'], ROOTDIR . $path)) {
                        $return['status'] = 'error';
                        $return['msg'] = 'Es ist ein Fehler beim Hochladen der Map aufgetreten.';
                    } else {
                        //altes Bild löschen
                        @unlink(ROOTDIR . $map['map_picture']);
                    }
                }



                if ($return['status'] != 'error') {


                    $update = $db->query("UPDATE " . TABLE_MAPS . " SET map_building_id = :map_building_id, map_floor = :map_floor, map_picture = :map_picture WHERE map_id = :map_id", array(
                        "map_building_id" => $data['map_building_id'],
                        "map_floor" => $data['map_floor'],
                        "map_picture" => $path,
                        "map_id" => $id
                    ));


                    $return['status'] = 'success';
                    $return['msg'] = 'Das Gebäude wurde bearbeitet.';
                }
            }
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es wurden keine Daten übertragen.';
        }
        return json_encode($return);
    }

    /**
     * Maßstab der Map bearbeiten
     * @global type $db
     * @param type $data
     * @param type $id
     * @return type
     */
    public function updateMapScale($data = '', $id = 0) {

        global $db;

        $return = array("status" => "", "msg" => "");

        if (isset($data) && !empty($data) && !empty($id)) {

            if (empty($data['map_scale_px']) || !is_numeric($data['map_scale_cm'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie bitte eine Pixel-Anzahl an.';
            }
            if (empty($data['map_scale_cm']) || !is_numeric($data['map_scale_cm'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Geben Sie eine Zentimeter-Zahl an.';
            }
            /* if (!$this->isValidToken(@$data['token'])) {
              $return['status'] = 'error';
              $return['msg'] = 'Token abgelaufen.';
              }
              $this->newToken(); */

            if ($return['status'] != 'error') {
                $update = $db->query("UPDATE " . TABLE_MAPS . " SET map_scale_px = :map_scale_px, map_scale_cm = :map_scale_cm WHERE map_id = :map_id", array(
                    "map_scale_px" => $data['map_scale_px'],
                    "map_scale_cm" => $data['map_scale_cm'],
                    "map_id" => $id
                ));

                $return['status'] = 'success';
                $return['msg'] = 'Das Maßstab wurde bearbeitet.';
            }
        }
        return json_encode($return);
    }

}
