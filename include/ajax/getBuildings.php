<?php

include_once '../config.php';

header('Content-Type: application/json');
$buildings = array();

if ($userData->isLoggedIn()) {
    if (isset($_GET['all'])) {
        http_response_code(200);
        $buildings['buildings'] = $db->query("SELECT * FROM " . TABLE_BUILDINGS);
        $buildings['status'] = 'success';
    } else if(isset($_GET['building_type'])){
        http_response_code(200);
        $db->bind("building_type", $_GET['building_type']);
        $buildings['buildings'] = $db -> query("SELECT * FROM " . TABLE_BUILDINGS . " WHERE building_type = :building_type");
        $buildings['status'] = 'success';
    }
    else {
        http_response_code(401);
        $buildings['status'] = 'error';
        $buildings['msg'] = 'Keine gültige Abfrage';
    }
} else {
    http_response_code(401);
    $buildings['status'] = 'error';
    $buildings['msg'] = 'nicht eingeloggt';
}

echo json_encode($buildings);
