<?php
$row = 1;
if (($handle = fopen("daten.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        echo "<p> $num Felder in Zeile $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";
        }
    }
    fclose($handle);
}

/*
    Zähler
    Index
    Erfassungsdatum
    Land-KZ
    Liegenschaft
    Raumnutzung
    a1
    
    Liegenschaft
    a1
    Anzahl AP im Quell-Raum1
    a3
    Name UZK
    Nachname MA / Raumbezeichung
    Vorname MA
    Titel
    Tel. MA
    a4
    Anzahl (nur für Eintragung)
    Inventar-Nr.
    Barcode
    Bild Nr 2 - 6
    Skizze Nr.
*/
?>