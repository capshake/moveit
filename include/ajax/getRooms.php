<?php

include_once '../config.php';

header('Content-Type: application/json');
$rooms = array();

if ($userData->isLoggedIn()) {
    if (isset($_GET['map_id'])) {
        http_response_code(200);
        $db->bind("map_id", $_GET['map_id']);
        $rooms['rooms'] = $db->query("SELECT * FROM " . TABLE_ROOMS . " WHERE room_map_id = :map_id");
        $rooms['status'] = 'success';
    } else if (isset($_GET['building_id'])) {
        http_response_code(200);
        $db->bind("building_id", $_GET['building_id']);
        $rooms['rooms'] = $db->query("SELECT * FROM " . TABLE_ROOMS . " LEFT JOIN " . TABLE_MAPS . " ON room_map_id = map_id WHERE map_building_id = :building_id");
        $rooms['status'] = 'success';
    } else if (isset($_GET['all'])) {
        http_response_code(200);
        $rooms['rooms'] = $db->query("SELECT * FROM " . TABLE_ROOMS);
        $rooms['status'] = 'success';
    } else {
        http_response_code(401);
        $rooms['status'] = 'error';
        $rooms['msg'] = 'Keine gültige Abfrage';
    }
} else {
    http_response_code(401);
    $rooms['status'] = 'error';
    $rooms['msg'] = 'nicht eingeloggt';
}

echo json_encode($rooms);
