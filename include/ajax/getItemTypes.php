<?php

include_once '../config.php';

header('Content-Type: application/json');
$items = array();

if ($userData->isLoggedIn()) {
    if (isset($_GET['type_id'])) { // Item Tyes
        http_response_code(200);
        $db->bind("type_id", $_GET['type_id']);
        $items['types'] = $db->query("SELECT * FROM " . TABLE_ITEM_TYPES . " WHERE item_type_id = :type_id");
        $items['status'] = 'success';
    } else if (isset($_GET['all'])) { // Item nach ID suchen
        http_response_code(200);
        $itemTypes = $db->query("SELECT * FROM " . TABLE_ITEM_TYPES . "");
        
        foreach($itemTypes as $type) {
            $items['types'][$type['item_type_id']] = $type; 
        }
        
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
