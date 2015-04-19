<?php

include_once '../config.php';

header('Content-Type: application/json');
$buildings = array();

if ($userData->isLoggedIn()) {
    if (isset($_GET['all'])) {
        header("HTTP/1.1 200 OK");
        $buildings['buildings'] = $db->query("SELECT * FROM " . TABLE_BUILDINGS);
        $buildings['status'] = 'success';
    } else if(isset($_GET['building_type'])){
        header("HTTP/1.1 200 OK");
        $db->bind("building_type", $_GET['building_type']);
        $buildings['buildings'] = $db -> query("SELECT * FROM " . TABLE_BUILDINGS . " WHERE building_type = :building_type");
        $buildings['status'] = 'success';
    }
    else {
        header("HTTP/1.1 401 OK");
        $buildings['status'] = 'error';
        $buildings['msg'] = 'Keine gültige Abfrage';
    }
} else {
    header("HTTP/1.1 401 OK");
    $buildings['status'] = 'error';
    $buildings['msg'] = 'nicht eingeloggt';
}

echo json_encode($buildings);
