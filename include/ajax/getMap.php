<?php

include_once '../config.php';

header('Content-Type: application/json');
$rooms = array();

if ($userData->isLoggedIn()) {
    if (isset($_GET['map_id'])) {
        header("HTTP/1.1 200 OK");
        $maps['maps'] = $db->query("SELECT map_id, map_building_id, map_floor, map_picture, map_scale_cm, map_scale_px, building_name FROM " . TABLE_MAPS . " LEFT JOIN " . TABLE_BUILDINGS . " ON map_building_id = building_id WHERE building_type = 2 AND map_id = :mapId", array('mapId' => $_GET['map_id']));

        $maps['status'] = 'success';
    } else {
        header("HTTP/1.1 200 OK");
        $maps['status'] = 'error';
        $maps['msg'] = 'ungültig';
    }
} else {
    header("HTTP/1.1 401 OK");
    $maps['status'] = 'error';
    $maps['msg'] = 'nicht eingeloggt';
}

echo json_encode($maps);
