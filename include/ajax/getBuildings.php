<?php

include_once '../config.php';

header('Content-Type: application/json');
$rooms = array();

if ($userData->isLoggedIn()) {
    if (isset($_GET['all'])) {
        http_response_code(200);
        $rooms['buildings'] = $db->query("SELECT * FROM " . TABLE_BUILDINGS);
        $rooms['status'] = 'success';
    } else {
        http_response_code(401);
        $rooms['status'] = 'error';
        $rooms['msg'] = 'Keine gültige Abfrage';
    }
} else {
    http_response_code(401);
    $rooms['status'] = 'error';
    $rooms['msg'] = 'nicht eingeloggt';
}

echo json_encode($rooms);