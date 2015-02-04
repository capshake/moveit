<?php

include_once '../config.php';

header('Content-Type: application/json');
$rooms = array();

if ($userData->isLoggedIn()) {
    if (isset($_GET['not_map_id'])) {
        http_response_code(200);
        $db->bind("map_id", $_GET['not_map_id']);
        $rooms['rooms'] = $db->query("SELECT room_id, room_name, room_name_alt, room_position_x, room_position_y,room_size_x,room_size_y, room_map_id, map_scale_cm AS room_scale_cm, map_scale_px AS room_scale_px FROM " . TABLE_ROOMS . " LEFT JOIN " . TABLE_MAPS . " ON room_map_id = map_id WHERE room_map_id != :map_id");
        $rooms['status'] = 'success';
    } else if (isset($_GET['room_not_in_map'])) {
        http_response_code(200);
        $db->bind("map_id", $_GET['room_not_in_map']);
        $rooms['rooms'] = $db->query("SELECT room_id, room_name, room_name_alt, room_position_x, room_position_y,room_size_x,room_size_y, room_map_id, map_scale_cm AS room_scale_cm, map_scale_px AS room_scale_px 
        FROM " . TABLE_ROOMS . " LEFT JOIN " . TABLE_MAPS . " ON room_map_id = map_id 
        WHERE room_map_id = :map_id 
        AND (room_position_x IS NULL 
        AND room_position_y IS NULL) 
        OR (room_position_x = 0 
        AND room_position_y = 0)");
        $rooms['status'] = 'success';
        
        
        
    } else if (isset($_GET['map_id'])) {
        http_response_code(200);
        $db->bind("map_id", $_GET['map_id']);
        $rooms['rooms'] = $db->query("SELECT room_id, room_name, room_name_alt, room_position_x, room_position_y,room_size_x,room_size_y FROM " . TABLE_ROOMS . " WHERE room_map_id = :map_id");
        $db->bind("map_id", $_GET['map_id']);
        $map = $db->row("SELECT map_id, map_scale_cm AS room_scale_cm, map_scale_px AS room_scale_px FROM " . TABLE_ROOMS . " LEFT JOIN " . TABLE_MAPS . " ON room_map_id = map_id WHERE room_map_id = :map_id");
        
        $rooms['map'] = $map;
        $rooms['status'] = 'success';
    } else if (isset($_GET['room_id'])) {
        http_response_code(200);
        $db->bind("room_id", $_GET['room_id']);
        $rooms['rooms'] = $db->query("SELECT * FROM " . TABLE_ROOMS . " WHERE room_id = :room_id");
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
