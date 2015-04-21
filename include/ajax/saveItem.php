<?php

include_once '../config.php';

header('Content-Type: application/json');
$items = array();

if ($userData->isLoggedIn()) {
    header("HTTP/1.1 200 OK");

    if (isset($_POST['itemid']) && isset($_POST['orientation'])) {

        $update = $db->query("UPDATE " . TABLE_ITEMS . " SET item_orientation = :item_orientation WHERE item_id = :item_id",
                                    array('item_id' => $_POST['itemid'],
                                          'item_orientation' => $_POST['orientation']));

        $items['msg'] = 'Item gespeichert';
        $items['status'] = 'success';
    } else if(isset($_POST['roomid']) && isset($_POST['itemid']) && isset($_POST['x']) && isset($_POST['y']) && isset($_POST['z'])){

        $update = $db->query("UPDATE " . TABLE_ITEMS . " SET item_position_x = :x, item_position_y = :y, item_position_z = :z, item_room_id = :room_id WHERE item_id = :item_id",
                                    array('room_id' => $_POST['roomid'],
                                          'item_id' => $_POST['itemid'],
                                          'x' => $_POST['x'],
                                          'y' => $_POST['y'],
                                          'z' => $_POST['z']));

        $items['msg'] = 'Item gespeichert';
        $items['status'] = 'success';
    } else {

        $items['msg'] = 'Felder vergessen';
        $items['status'] = 'error';
    }
} else {
    header("HTTP/1.1 401 OK");
    $items['status'] = 'error';
    $items['msg'] = 'nicht eingeloggt';
}

echo json_encode($items);
