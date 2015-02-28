<?php

include_once '../config.php';

header('Content-Type: application/json');
$items = array();

if ($userData->isLoggedIn()) {
    if (isset($_GET['room_id'])) { // Items nach Raum suchen
        http_response_code(200);
        $db->bind("room_id", $_GET['room_id']);
        $items['items'] = $db->query("SELECT " . TABLE_ITEMS . ".* FROM " . TABLE_ITEMS . "," . TABLE_ROOMS . " WHERE item_room_id = room_id AND room_id = :room_id");
        $items['status'] = 'success';
    } else if (isset($_GET['item_id'])) { // Item nach ID suchen
        http_response_code(200);
        $db->bind("item_id", $_GET['item_id']);
        $items['items'] = $db->query("SELECT * FROM " . TABLE_ITEMS . " WHERE item_id = :item_id");
        $items['status'] = 'success';
    } else {
        http_response_code(401);
        $items['status'] = 'error';
        $items['msg'] = 'Keine gültige Abfrage';
    }
} else {
    http_response_code(401);
    $items['status'] = 'error';
    $items['msg'] = 'nicht eingeloggt';
}

echo json_encode($items);
