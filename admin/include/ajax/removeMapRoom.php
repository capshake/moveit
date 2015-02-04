<?php

include_once '../../../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    if (isset($_POST['token'])) {
        if (strtolower($_SERVER['REQUEST_METHOD']) != 'post' || (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) {
            exit;
        }
        header('Content-type: application/json');


        if (isset($_POST['room_id'])) {
            $db->query("UPDATE " . TABLE_ROOMS . " SET room_position_x = :room_position_x, room_position_y = :room_position_y, room_size_x = :room_size_x, room_size_y = :room_size_y WHERE room_id = :room_id", array(
                "room_position_x" => 'NULL',
                "room_position_y" => 'NULL',
                "room_size_x" => 'NULL',
                "room_size_y" => 'NULL',
                "room_id" => $_POST['room_id'])
            );
        }

        $return = array('status' => 'success', 'msg' => 'Der Raum wurde von der Karte entfernt.');

        echo json_encode($return);
    }
}
        
        
        
        
