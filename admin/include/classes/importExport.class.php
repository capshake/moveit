<?php

class importExport {

    private $filename = '';

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
    public function truncate() {
        global $db;

        $return = array("status" => "", "msg" => "");

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

        return json_encode($return);
    }

    /**
     * Import
     * @global type $db
     * @return type
     */
    public function import() {
        global $db;

        $return = array("status" => "", "msg" => "");

        if (file_exists($this->filename)) {
            $file = fopen($this->filename, "r");
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
                        if (mysql_errno() == 1062) {
                            $query_ad_anzahl = "SELECT AD_Anzahl FROM data_import WHERE B__Index = " . $data[1];
                            $var_ad_anzahl = mysql_result(mysql_query($query_ad_anzahl), 0, 'AD_Anzahl');

                            $query_aj_volumen = "SELECT AJ_Volumen in cbm FROM data_import WHERE B__Index = " . $data[1];
                            $var_aj_volumen = mysql_result(mysql_query($query_aj_volumen), 0, 'AJ_Volumen in cbm');

                            $query_update = "UPDATE data_import SET B__Index = " . $data[1] . " ,AD_Anzahl = " . $data[29] . " + " . $var_ad_anzahl . " , " . "AJ_Volumen in cbm = " . str_replace(",", ".", $data[35]) . " + " . $var_aj_volumen . " WHERE B__Index = " . $data[1] . ";";
                            $db->query($query_update);
                        }

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
            mysql_close($db);
        }

        return json_encode($return);
    }

}
