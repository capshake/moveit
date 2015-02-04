<?php

include_once '../../../include/config.php';

if ($userData->isLoggedIn() && $userData->isAdmin()) {
    if (isset($_POST['token'])) {
        if (strtolower($_SERVER['REQUEST_METHOD']) != 'post' || (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) {
            exit;
        }
        header('Content-type: application/json');

        $map = $db->row("SELECT * FROM " . TABLE_MAPS . " WHERE map_id = :mapId", array("mapId" => $_POST['map_id']));

        if (isset($_POST['map_id']) && isset($_POST['rooms']) && $map['map_id']) {
            
            $scaleOnePixel = $map['map_scale_cm'] / $map['map_scale_px'];
            
            
            foreach ($_POST['rooms'] as $room) {
                
                
                
                $db->query("UPDATE " . TABLE_ROOMS . " SET "
                        . "room_position_y = :room_position_y,
                   room_position_x = :room_position_x,
                   room_size_x = :room_size_x,
                   room_size_y = :room_size_y, room_map_id = :room_map_id WHERE room_id = :room_id", array(
                    "room_position_y" => $room['room_position_y'],
                    "room_position_x" => $room['room_position_x'],
                    "room_size_y" => $room['room_size_y']*$scaleOnePixel,
                    "room_size_x" => $room['room_size_x']*$scaleOnePixel,
                    "room_map_id" => $_POST['map_id'],
                    "room_id" => $room['room_id'])
                );
            }
        }
        
        $return = array('status' => 'success', 'msg' => 'Die Karte wurde bearbeitet.');
        
        echo json_encode($return);
    }
}
