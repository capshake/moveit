<?php

include_once '../config.php';

header('Content-Type: application/json');
$items = array();

if ($userData->isLoggedIn()) {
    if (isset($_GET['item_id'])) { // Items nach Raum suchen
        header("HTTP/1.1 200 OK");
        $db->bind("item_id", $_GET['item_id']);
        $items['item'] = $db->query("SELECT `AN_Farbe_Sitz-Lehne_Bezug/Material_Platte/Farbe_Deckplatte/Farbe` AS farbe, `D__Dezernat\/Fachbereich` AS fachbereich, item_description, item_size_x AS width, item_size_y AS height, AV_Bemerkungen AS bemerkung FROM " . TABLE_ITEMS . " LEFT JOIN " . TABLE_IMPORT . " ON B__Index = item_import_id WHERE item_id = :item_id");
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
