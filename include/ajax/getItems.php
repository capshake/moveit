<?php

include_once '../config.php';

header('Content-Type: application/json');
$items = array();

if ($userData->isLoggedIn()) {
    if (isset($_GET['room_id'])) { // Items nach Raum suchen
        header("HTTP/1.1 200 OK");
        $db->bind("room_id", $_GET['room_id']);
        $items['items'] = $db->query("SELECT " . TABLE_ITEMS . ".* FROM " . TABLE_ITEMS . "," . TABLE_ROOMS . " WHERE item_room_id = room_id AND room_id = :room_id");
        $items['owner'] = isOwnerOfRoom($_GET['room_id'], $_SESSION['user_id']);
        $items['status'] = 'success';
    } else if (isset($_GET['item_id'])) { // Item nach ID suchen
        header("HTTP/1.1 200 OK");
        $db->bind("item_id", $_GET['item_id']);
        $items['items'] = $db->query("SELECT * FROM " . TABLE_ITEMS . " WHERE item_id = :item_id");
        $items['status'] = 'success';
    } else if (isset($_GET['store'])) { //Items aus Lager eines angegebenen Users suchen
        header("HTTP/1.1 200 OK");
        if($_GET['store'] == 'user'){
            $db->bind("store_user", 'store_' . $_SESSION['user_id']);
        } else if($_GET['store'] == 'all'){
            $db->bind("store_user", 'store_all');
        }

        $items['items'] = $db -> query("SELECT " . TABLE_ITEMS . ".* FROM " . TABLE_ITEMS . "," . TABLE_ROOMS . " WHERE item_room_id = room_id AND room_id = (SELECT room_id FROM rooms WHERE room_name = :store_user)");
        $items['status'] = 'success';
    } else if (isset($_GET['trash'])) { //Items aus Lager eines angegebenen Users suchen
        header("HTTP/1.1 200 OK");
        $db->bind("trash_user", 'trash_' . $_SESSION['user_id']);
        $items['items'] = $db -> query("SELECT " . TABLE_ITEMS . ".* FROM " . TABLE_ITEMS . "," . TABLE_ROOMS . " WHERE item_room_id = room_id AND room_id = (SELECT room_id FROM rooms WHERE room_name = :trash_user)");
        $items['status'] = 'success';
    }  else if (isset($_GET['virtualRooms'])) { //Items aus Lager eines angegebenen Users suchen
        header("HTTP/1.1 200 OK");
        $vrooms = $db -> query("SELECT room_id, room_name FROM " . TABLE_ROOMS . " WHERE room_name = 'store_".$_SESSION['user_id']."' OR room_name = 'trash_".$_SESSION['user_id']."' OR room_name = 'store_all' ");
        
        foreach($vrooms as $v) {
            if($v['room_name'] != 'store_all' ) {
                $items[substr($v['room_name'], 0, 5)] = $v['room_id'];
            } else {
                $items[$v['room_name']] = $v['room_id'];
            }
            
            
        }
        
        $items['status'] = 'success';
    }else {
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
