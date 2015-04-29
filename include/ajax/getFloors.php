<?php

include_once '../config.php';

header('Content-Type: application/json');
$floors = array();

if ($userData->isLoggedIn()) {
    if(isset($_GET['building_id'])){
        header("HTTP/1.1 200 OK");
        $db->bind("building_id", $_GET['building_id']);
        $floors['floors'] = $db -> query("SELECT map_id, map_floor, map_picture FROM " . TABLE_MAPS . " WHERE map_building_id = :building_id");
        $floors['status'] = 'success';
    }
    else if(isset($_GET['floor_id'])){
        header("HTTP/1.1 200 OK");
        $db->bind("floor_id", $_GET['floor_id']);
        $floors['floors'] = $db -> query("SELECT map_id, map_floor, map_picture FROM " . TABLE_MAPS . " WHERE map_id = :floor_id");
        $floors['status'] = 'success';
    }
    else {
        header("HTTP/1.1 401 OK");
        $floors['status'] = 'error';
        $floors['msg'] = 'Keine gültige Abfrage';
    }
} else {
    header("HTTP/1.1 401 OK");
    $floors['status'] = 'error';
    $floors['msg'] = 'nicht eingeloggt';
}

echo json_encode($floors);
