<?php

include_once '../config.php';

header('Content-Type: application/json');
$items = array();

if ($userData->isLoggedIn()) {
    http_response_code(200);
    
    if (isset($_POST['roomid']) && isset($_POST['itemid']) && isset($_POST['x']) && isset($_POST['y'])) {
        

        $update = $db->query("UPDATE " . TABLE_ITEMS . " SET item_position_x = :x, item_position_y = :y, item_room_id = :room_id WHERE item_id = :item_id",
                                    array('room_id' => $_POST['roomid'], 
                                          'item_id' => $_POST['itemid'], 
                                          'x' => $_POST['x'], 
                                          'y' => $_POST['y']));
        
        
        $items['msg'] = 'Item wurde geupdated';
        $items['status'] = 'success';
    } else {
        
        $items['msg'] = 'Felder vergessen';
        $items['status'] = 'error';
    }
} else {
    http_response_code(401);
    $items['status'] = 'error';
    $items['msg'] = 'nicht eingeloggt';
}

echo json_encode($items);
