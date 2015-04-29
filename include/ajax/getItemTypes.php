<?php

include_once '../config.php';

header('Content-Type: application/json');
$items = array();

if ($userData->isLoggedIn()) {
    if (isset($_GET['type_id'])) { // Item Tyes
        header("HTTP/1.1 200 OK");
        $db->bind("type_id", $_GET['type_id']);
        $items['types'] = $db->query("SELECT * FROM " . TABLE_ITEM_TYPES . " WHERE item_type_id = :type_id");
        $items['status'] = 'success';
    } else if (isset($_GET['all'])) { // Item nach ID suchen
        header("HTTP/1.1 200 OK");
        $itemTypes = $db->query("SELECT * FROM " . TABLE_ITEM_TYPES . "");
        
        foreach($itemTypes as $type) {
            $items['types'][$type['item_type_id']] = $type; 
        }
        
        $items['status'] = 'success';
    } else {
        header("HTTP/1.1 401 OK");
        $items['status'] = 'error';
        $items['msg'] = 'Keine gültige Abfrage';
    }
} else {
    header("HTTP/1.1 401 OK");
    $items['status'] = 'error';
    $items['msg'] = 'nicht eingeloggt';
}

echo json_encode($items);
