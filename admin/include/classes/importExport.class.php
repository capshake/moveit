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

        // Inhalt der Tabellen löschen
        $db->query("CALL `proc_truncate_tables`();");

        // Nutzerräume wiederherstellen
        $db->query("CALL `proc_restore_user_rooms`();");

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
                        $insertdata = array();

                        $data[35] = str_replace(",", ".", $data[35]); //Kommanotation ändern

                        // TODO: Richtiges Exception-Handling (Das hier ist viel zu langsam!!). Prüfen auf MySQL Fehler 1062
                        // Prüfen, ob ein Item schon vorhanden ist, wenn ja, nur Anzahl und Volumen aufaddieren
                        if ($data[1] != NULL && $db->single("SELECT `B__Index` FROM " . TABLE_IMPORT . " WHERE `B__Index` = :data1", array('data1' => $data[1]))) { //Sollte der Eintrag schon existieren, Anzahl updaten
                            $query_ad_anzahl = "SELECT AD_Anzahl FROM data_import WHERE B__Index = :data1";
                            $var_ad_anzahl = $db->single($query_ad_anzahl, array('data1' => $data[1]));

                            $query_aj_volumen = "SELECT `AJ_Volumen in cbm` FROM data_import WHERE B__Index = :data1";
                            $var_aj_volumen = $db->single($query_aj_volumen, array('data1' => $data[1]));
                            
                            $db->query("UPDATE data_import SET B__Index = :data1 ,AD_Anzahl = :data29 + :varanzahl , " . "`AJ_Volumen in cbm` = :data35 + :varvolumen WHERE B__Index = :data11;", 
                                    array('data1' => $data[1], 'data29' => $data[29], 'varanzahl' => $var_ad_anzahl, 'data35' => str_replace(",", ".", $data[35]), 'varvolumen' => $var_aj_volumen, 'data11' => $data[1]));
                        }
                        elseif($data[1] != NULL){
                            for ($c = 0; $c < 59; $c++) {
                                if (empty($data[$c])) {
                                    $query = $query . "NULL"; // leere Spalten als NULL eintragen
                                }
                                else{
                                    $query = $query . ":" . $c;
                                    $insertdata[$c] = $data[$c];
                                }

                                if ($c != 58) {
                                    $query = $query . ", "; //Nächste Spalte anhängen
                                }
                            }

                        $query = $query . ")";
                        $db->query($query, $insertdata);
                        }
                    
                    }

                    $data = fgetcsv($file, 0, ";"); //nächste Zeile holen
                }
            }
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
        if ($this->truncateDB()) {
            $return['status'] = 'success';
            $return['msg'] = 'Datenbank erfolgreich geleert';
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
            return true;
        } else {
            return false;
        }
    }

    /**
     * Export starten
     * @return type
     */
    public function export() {
        $return = array("status" => "", "msg" => "");
        if ($this->exportDB()) {
            $return['status'] = 'success';
            $return['msg'] = 'Export erfolgreich durchgeführt';
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

                    if ($this->import()) {
                        $return['status'] = 'success';
                        $return['msg'] = 'CSV erfolgreich hochgeladen und importiert';
                    } else {
                        $return['status'] = 'error';
                        $return['msg'] = 'Es ist ein Fehler aufgetreten';
                    }
                }
            }
        } else {
            $return['status'] = 'error';
            $return['msg'] = 'Es wurden keine Daten übertragen';
        }
        return json_encode($return);
    }

}
