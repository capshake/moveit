<?php

class importExport extends Token {

    private $importFile = 'uploads/csv/import.csv';
    private $exportFile = 'uploads/csv/export.csv';

    /**
     * Konstruktor
     */
    public function __construct() {
        
    }

    /**
     * Truncate
     * @global type $db
     * @return type
     */
    private function truncateDB() {

        global $db;

        $db->query("DELETE FROM " . TABLE_ITEMS . ";");
        $db->query("DELETE FROM " . TABLE_ROOMS . ";");
        $db->query("DELETE FROM " . TABLE_MAPS . ";");
        $db->query("DELETE FROM " . TABLE_IMPORT . ";");
        $db->query("DELETE FROM " . TABLE_DEPARTMENTS . ";");
        $db->query("DELETE FROM " . TABLE_BUILDINGS . ";");

        $db->query("ALTER TABLE " . TABLE_ITEMS . " AUTO_INCREMENT = 1;");
        $db->query("ALTER TABLE " . TABLE_ROOMS . " AUTO_INCREMENT = 1;");
        $db->query("ALTER TABLE " . TABLE_MAPS . " AUTO_INCREMENT = 1;");
        $db->query("ALTER TABLE " . TABLE_IMPORT . " AUTO_INCREMENT = 1000000;");
        $db->query("ALTER TABLE " . TABLE_DEPARTMENTS . " AUTO_INCREMENT = 1;");
        $db->query("ALTER TABLE " . TABLE_BUILDINGS . " AUTO_INCREMENT = 1;");

        return true;
    }

    /**
     * Import
     * @global type $db
     * @return type
     */
    private function importDB() {

        global $db;

        $return = array("status" => "", "msg" => "");

        if (file_exists(ROOTDIR . $this->importFile)) {
            $file = fopen(ROOTDIR . $this->importFile, "r");
            if ($file) {
                $data = fgetcsv($file, 0, ";");

                $row = 0;
                while ($data != false) {
                    if ($data[0] == 1) {
                        $row++;
                        $col = count($data);
                        $query = "INSERT INTO " . TABLE_IMPORT . " VALUES(";
                        $query_update = "";

                        for ($c = 0; $c < $col; $c++) {
                            // NULL-Spalten als NULL eintragen
                            if ($data[$c] == "") {
                                $query = $query . "NULL";
                            } elseif ($c == 35) { //Spalte "Volumen"
                                //Kommanotation ändern
                                $query = $query . "'" . str_replace(",", ".", $data[$c]) . "'";
                            } else {
                                $query = $query . "'" . $data[$c] . "'";
                            }

                            if ($c < $col - 1) {
                                $query = $query . ",";
                            }
                        }

                        $query = $query . ")";
                        $db->query($query);

                        // Sollte es diesen Eintrag schon geben Anzahl und Volumen updaten
                        /* if (mysql_errno() == 1062) {
                          $query_ad_anzahl = "SELECT AD_Anzahl FROM data_import WHERE B__Index = " . $data[1];
                          $var_ad_anzahl = mysql_result(mysql_query($query_ad_anzahl), 0, 'AD_Anzahl');

                          $query_aj_volumen = "SELECT AJ_Volumen in cbm FROM data_import WHERE B__Index = " . $data[1];
                          $var_aj_volumen = mysql_result(mysql_query($query_aj_volumen), 0, 'AJ_Volumen in cbm');

                          $query_update = "UPDATE data_import SET B__Index = " . $data[1] . " ,AD_Anzahl = " . $data[29] . " + " . $var_ad_anzahl . " , " . "AJ_Volumen in cbm = " . str_replace(",", ".", $data[35]) . " + " . $var_aj_volumen . " WHERE B__Index = " . $data[1] . ";";
                          $db->query($query_update);
                          } */

                        //DEBUG
                        if (mysql_errno() != 0) {
                            if ($query_update != "") {
                                echo '<p>' . $query_update . '<br>';
                            } else {
                                echo '<p>' . $query . '<br>';
                            }
                            echo mysql_errno() . ':' . mysql_error() . '</p><br>';
                        }

                        /* DEBUG (nur alle neuen Datensätze)
                          if($query_update == ""){
                          print("<p>" . $query . "<br>");
                          } */
                    }
                    $data = fgetcsv($file, 0, ";"); //nächste Zeile holen
                }
            }

            //Tabelle anzeigen
            $numrows = $db->query("SELECT * FROM data_import", PDO::FETCH_NUM);

            print("Anzahl Reihen:" . $numrows . "<br>");

            fclose($file);


            return true;
        }

        return json_encode($return);
    }

    /**
     * Exportiere DB
     * @global type $db
     */
    private function exportDB() {

        global $db;

        $data = $db->query("SELECT * FROM data_export ORDER BY B__Index, AD_Anzahl DESC", null, PDO::FETCH_NUM);

        //Array der Spaltennamen (Lässt sich wegen zu langer Namen nicht aus DB holen)
        $cols = array(
            0 => 'Zähler',
            1 => 'Index',
            2 => 'Erfassungsdatum',
            3 => 'Dezernat/Fachbereich',
            4 => 'Raumnutzungsart',
            5 => 'Land-KZ',
            6 => 'Liegenschafts-Nr. Bestand',
            7 => 'Bauteil-Nr. Bestand',
            8 => 'Etage Bestand',
            9 => 'Raum-Nr. Bestand',
            10 => 'AP-Nr. Bestand',
            11 => 'a1',
            12 => 'Liegenschafts-Nr neu',
            13 => 'Bauteil-Nr. neu',
            14 => 'Etage neu',
            15 => 'Raum-Nr. neu (PPD-Nr.)',
            16 => 'Raum-Nr. neu (Raum-ID)',
            17 => 'AP-Nr. neu',
            18 => 'a1',
            19 => 'Anzahl AP im Quell-Raum',
            20 => 'a3',
            21 => 'Name UZK',
            22 => 'Nachname MA / Raumbezeichnung',
            23 => 'Vorname MA',
            24 => 'Titel',
            25 => 'Tel. MA',
            26 => 'a4',
            27 => 'Kürzel',
            28 => 'Code',
            29 => 'Anzahl',
            30 => 'Bezeichnung',
            31 => 'Cluster Bezeichnung',
            32 => 'B',
            33 => 'T',
            34 => 'H',
            35 => 'Volumen in cbm',
            36 => 'Hersteller/',
            37 => 'Modell/ Ausfuehrung/Material/Fuss/Form/hv/Fuss /Türart/Anzahl Ablagen u.a.',
            38 => 'Sitz-Lehne-Bezug/Polsterung/Sockel/u.a.',
            39 => 'Farbe_Sitz-Lehne_Bezug/Material_Platte/Farbe_Deckplatte/Farbe_Schirm/Form/Farbe_Oberfläche u.a.',
            40 => 'Rechnername/Zollgroesse/Druckername/Montageart/Farbe_Korpus/Farbe_Platte',
            41 => 'Seitenverh./Tel-Fax-Nr/Farbe_Fuss_/Farbe_Gestell/Farbe_Front/Farbe_Tuer',
            42 => 'Weitere Eigenschaft/Rollentyp/drehbar/stapelbar/Rahmenausfuehrung/ Links-Rechts/ Winkel/Umleimer-Kante',
            43 => 'Zustand',
            44 => 'Umzug (1/0)',
            45 => 'De(na) Remon(na)tage erfor(na)derlich',
            46 => 'Inventar-Nr.',
            47 => 'Bemerkungen',
            48 => 'Barcode',
            49 => 'Bild Nr 1',
            50 => 'Bild Nr 2',
            51 => 'Bild Nr 3',
            52 => 'Bild Nr 4',
            53 => 'Bild Nr 5',
            54 => 'Bild Nr 6',
            55 => 'Skizze Nr.',
            56 => '',
            57 => '',
            58 => '',
        );

        header('Content-Type: application/excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="export_' . time() . '.csv"');

        $file = fopen('php://output', 'w');

        fprintf($file, "\xEF\xBB\xBF"); //UTF-8 BOM
        fputcsv($file, $cols, ";"); //Spaltennamen

        foreach ($data as $row) {
            //Dezimalzahlen wieder in deutsches Format bringen
            $row[35] = str_replace(".", ",", $row[35]);

            fputcsv($file, $row, ";");
        }
        fclose($file);
        exit;

        return true;
    }

    /**
     * Reset
     * @return type
     */
    public function reset() {
        $return = array("status" => "", "msg" => "");

        if ($this->truncateDB() && $this->importDB()) {
            $return['status'] = 'success';
            $return['msg'] = 'Reset erfolgreich gestartet';
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es ist ein Fehler aufgetreten';
        }
        return json_encode($return);
    }

    /**
     * Import starten
     * @return type
     */
    public function import() {
        $return = array("status" => "", "msg" => "");

        if ($this->importDB()) {
            $return['status'] = 'success';
            $return['msg'] = 'Import erfolgreich gestartet';
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es ist ein Fehler aufgetreten';
        }

        return json_encode($return);
    }

    /**
     * Export starten
     * @return type
     */
    public function export() {
        $return = array("status" => "", "msg" => "");

        if ($this->exportDB()) {
            $return['status'] = 'success';
            $return['msg'] = 'Export erfolgreich gestartet';
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es ist ein Fehler aufgetreten';
        }

        return json_encode($return);
    }

    /**
     * Hochladen der CSV
     * @global type $db
     * @param type $data
     * @param type $files
     * @return type
     */
    public function uploadFile($data = '', $files = '') {

        global $db;

        $return = array("status" => "", "msg" => "");

        if (isset($data) && !empty($data)) {

            $ex = end(explode('.', $files['csv_file']['name']));

            if (!$this->isValidToken(@$data['token'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Token abgelaufen.';
            }
            $this->newToken();

            if (!isset($files['csv_file']['name']) && !empty($files['csv_file']['name'])) {
                $return['status'] = 'error';
                $return['msg'] = 'Bitte eine CSV-Datei angeben.';
            } else {
                $ex = end(explode('.', $files['csv_file']['name']));

                if (!in_array(strtolower($ex), array('csv'))) {
                    $return['status'] = 'error';
                    $return['msg'] = 'Nur CSV-Dateien.';
                }
            }

            if ($return['status'] != 'error') {
                //upload
                $path = 'uploads/csv/import.' . $ex;
                @unlink(ROOTDIR . $path);
                if (!move_uploaded_file($files['csv_file']['tmp_name'], ROOTDIR . $path)) {
                    $return['status'] = 'error';
                    $return['msg'] = 'Es ist ein Fehler beim hochladen aufgetreten.';
                } else {
                    $return['status'] = 'success';
                    $return['msg'] = 'CSV erfolgreich hochgeladen';
                }
            }
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es wurden keine Daten übertragen';
        }
        return json_encode($return);
    }

}
