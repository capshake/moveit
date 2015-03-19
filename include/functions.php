<?php

/**
 * Name der Etage erhalten
 * @param type $floor
 * @return string
 */
function getFloor($floor) {
    if ($floor > 0) {
        return $floor . '. Etage';
    } else if ($floor <= -1) {
        return ($floor*-1) . '. Untergeschoss';
    } else {
        return 'Erdgeschoss';
    }
}



/**
 * Prüfen ob Benutzer der Eigner des Raumes ist
 * @param type $roomId
 * @param type $userId
 * @return boolean
 */
function isOwnerOfRoom($roomId, $userId) {
    global $db;
    $existsRoomUser = $db->row("SELECT * FROM " . TABLE_USER_ROOMS . " WHERE role_room_user_id = :role_room_user_id AND role_room_room_id = :role_room_room_id", array(
            "role_room_user_id" => $userId,
            "role_room_room_id" => $roomId
        ), PDO::FETCH_NUM);
    
    if($existsRoomUser) {
        return true;
    }
    return false;
}