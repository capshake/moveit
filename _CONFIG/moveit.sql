-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 20. Feb 2015 um 13:00
-- Server Version: 5.6.20
-- PHP-Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `moveit`
--

DELIMITER $$
--
-- Prozeduren
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_restore_user_rooms`()
BEGIN
DECLARE var_finished INT DEFAULT 0;
DECLARE var_user_id INT;
DECLARE user_cursor CURSOR FOR SELECT user_id FROM `users`;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET var_finished = 1;

-- Falls nicht existent, Lager für alle Nutzer erstellen
IF NOT EXISTS(SELECT * FROM `rooms` WHERE `room_name` = 'store_all') THEN
	INSERT INTO `rooms` VALUES(NULL, 'store_all', NULL, NULL, NULL, NULL, NULL, NULL, 0);
END IF;

OPEN user_cursor;

loop_users: LOOP
	FETCH user_cursor INTO var_user_id;
    IF var_finished = 1 THEN
    	LEAVE loop_users;
    END IF;

    -- Lager aller Nutzer zugänglich machen
    INSERT INTO `user_role_room` VALUES (var_user_id, (SELECT room_id FROM rooms WHERE room_name = 'store_all'), 1);

    -- Lagerraum anlegen
    INSERT INTO `rooms` VALUES (NULL, CONCAT('store_', var_user_id), NULL, NULL, NULL, NULL, NULL, NULL, 0);

    INSERT INTO `user_role_room` VALUES (var_user_id, (SELECT room_id FROM rooms WHERE room_name = CONCAT('store_', var_user_id)), 1);

    -- Wunschliste anlegen
    INSERT INTO `rooms` VALUES (NULL, CONCAT('wishlist_', var_user_id), NULL, NULL, NULL, NULL, NULL, NULL, 0);

    INSERT INTO `user_role_room` VALUES (var_user_id, (SELECT room_id FROM rooms WHERE room_name = CONCAT('wishlist_', var_user_id)), 1);

	-- Müll anlegen
	INSERT INTO `rooms` VALUES (NULL, CONCAT('trash_', var_user_id), NULL, NULL, NULL, NULL, NULL, NULL, 0);

    INSERT INTO `user_role_room` VALUES (var_user_id, (SELECT room_id FROM rooms WHERE room_name = CONCAT('trash_', var_user_id)), 1);

END LOOP loop_users;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_truncate_tables`()
BEGIN

DELETE FROM `items`;
DELETE FROM `user_role_room`;
DELETE FROM `rooms`;
DELETE FROM `maps`;
DELETE FROM `data_import`;
DELETE FROM `departments`;
DELETE FROM `buildings`;

ALTER TABLE `items` AUTO_INCREMENT = 1;
ALTER TABLE `rooms` AUTO_INCREMENT = 1;
ALTER TABLE `user_role_room` AUTO_INCREMENT = 1;
ALTER TABLE `maps` AUTO_INCREMENT = 1;
ALTER TABLE `data_import` AUTO_INCREMENT = 1000000;
ALTER TABLE `departments` AUTO_INCREMENT = 1;
ALTER TABLE `buildings` AUTO_INCREMENT = 1;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `buildings`
--

CREATE TABLE IF NOT EXISTS `buildings` (
`building_id` int(11) unsigned NOT NULL,
  `building_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `building_type` tinyint(1) NOT NULL COMMENT '0 für virtuell (Wunschliste, Müll, Lager),1 für Bestand, 2 für Neubau'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `data_export`
--

CREATE TABLE IF NOT EXISTS `data_export` (
  `A__Zähler` int(11) NOT NULL COMMENT 'Dass der Wert nicht NULL sein darf schließt alle ungültigen Zeilen aus! (Z.B. Hinweise unter Tabelle in CSV)',
  `B__Index` int(11) NOT NULL,
  `C__Erfassungsdatum` varchar(16) CHARACTER SET utf8 DEFAULT NULL COMMENT 'VARCHAR, da in CSV nie genutzt und Datentyp unklar',
  `D__Dezernat\/Fachbereich` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `E__Raumnutzungsart` int(11) DEFAULT NULL,
  `F__Land-KZ` int(11) DEFAULT NULL,
  `G__Liegenschafts-Nr. Bestand` int(11) DEFAULT NULL,
  `H__Bauteil-Nr. Bestand` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `I__Etage Bestand` int(11) DEFAULT NULL,
  `J__Raum-Nr. Bestand` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `K__AP-Nr. Bestand` int(11) DEFAULT NULL,
  `L__a1` int(11) DEFAULT NULL,
  `M__Liegenschafts-Nr. neu` int(11) DEFAULT NULL,
  `N__Bauteil-Nr. neu` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `O__Etage neu` int(11) DEFAULT NULL,
  `P__Raum-Nr. neu (PPD-Nr.)` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `Q__Raum-Nr. neu (Raum-ID)` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `R__AP-Nr. neu` int(11) DEFAULT NULL,
  `S__a1` int(11) DEFAULT NULL,
  `T__Anzahl AP im Quell-Raum` int(11) DEFAULT NULL,
  `U__a3` int(11) DEFAULT NULL,
  `V__Name UZK` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `W__Nachname MA / Raumbezeichung` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `X__Vorname MA` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `Y__Titel` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `Z__Tel. MA` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AA_a4` int(11) DEFAULT NULL,
  `AB_Kürzel` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `AC_Code` int(11) DEFAULT NULL,
  `AD_Anzahl` int(11) DEFAULT NULL,
  `AE_Bezeichnung` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AF_Cluster Bezeichnung` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AG_B` int(11) DEFAULT NULL,
  `AH_T` int(11) DEFAULT NULL,
  `AI_H` int(11) DEFAULT NULL,
  `AJ_Volumen in cbm` decimal(10,4) DEFAULT NULL COMMENT 'Beim Export auf Umwandlung achten! (Zurück in deutsche Darstellung für Dezimalzeichen!)',
  `AK_Hersteller/` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AL_Modell/ Ausfuehrung/Material/Fuss/Form/hv/Fuss /Türart/Anzahl` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AL_Modell/ Ausfuehrung/Material/Fuss/Form/hv/Fuss /Türart/Anzahl Ablagen u.a.',
  `AM_Sitz-Lehne-Bezug/Polsterung/Sockel/u.a.` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AM_Sitz-Lehne-Bezug/Polsterung/Sockel/u.a.',
  `AN_Farbe_Sitz-Lehne_Bezug/Material_Platte/Farbe_Deckplatte/Farbe` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AN_Farbe_Sitz-Lehne_Bezug/Material_Platte/Farbe_Deckplatte/Farbe_Schirm/Form/Farbe_Oberfläche u.a.',
  `AO_Rechnername/Zollgroesse/Druckername/Montageart/Farbe_Korpus/F` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AO_Rechnername/Zollgroesse/Druckername/Montageart/Farbe_Korpus/Farbe_Platte',
  `AP_Seitenverh./Tel-Fax-Nr/Farbe_Fuss_/Farbe_Gestell/Farbe_Front/` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AP_Seitenverh./Tel-Fax-Nr/Farbe_Fuss_/Farbe_Gestell/Farbe_Front/Farbe_Tuer',
  `AQ_Weitere Eigenschaft/Rollentyp/drehbar/stapelbar/Rahmenausfueh` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AQ_Weitere Eigenschaft/Rollentyp/drehbar/stapelbar/Rahmenausfuehrung/ Links-Rechts/ Winkel/Umleimer-Kante',
  `AR_Zustand` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `AS_Umzug (1/0)` int(1) DEFAULT NULL,
  `AT_De(na) Remon(na)tage erfor(na)derlich` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `AU_Inventar-Nr.` int(11) DEFAULT NULL,
  `AV_Bemerkungen` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AW_Barcode` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AX_Bild Nr 1` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AY_Bild Nr 2` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AZ_Bild Nr 3` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `BA_Bild Nr 4` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `BB_Bild Nr 5` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `BC_Bild Nr 6` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `BD_Skizze Nr.` int(11) DEFAULT NULL,
  `BE` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Leer in CSV, muss drin sein damit Anzahl der Spalten überall gleich',
  `BF` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Leer in CSV, muss drin sein damit Anzahl der Spalten überall gleich',
  `BG` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Leer in CSV, muss drin sein damit Anzahl der Spalten überall gleich'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `data_import`
--

CREATE TABLE IF NOT EXISTS `data_import` (
  `A__Zähler` int(11) NOT NULL COMMENT 'Dass der Wert nicht NULL sein darf schließt alle ungültigen Zeilen aus! (Z.B. Hinweise unter Tabelle in CSV)',
`B__Index` int(11) NOT NULL,
  `C__Erfassungsdatum` varchar(16) CHARACTER SET utf8 DEFAULT NULL COMMENT 'VARCHAR, da in CSV nie genutzt und Datentyp unklar',
  `D__Dezernat\/Fachbereich` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `E__Raumnutzungsart` int(11) DEFAULT NULL,
  `F__Land-KZ` int(11) DEFAULT NULL,
  `G__Liegenschafts-Nr. Bestand` int(11) DEFAULT NULL,
  `H__Bauteil-Nr. Bestand` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `I__Etage Bestand` int(11) DEFAULT NULL,
  `J__Raum-Nr. Bestand` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `K__AP-Nr. Bestand` int(11) DEFAULT NULL,
  `L__a1` int(11) DEFAULT NULL,
  `M__Liegenschafts-Nr. neu` int(11) DEFAULT NULL,
  `N__Bauteil-Nr. neu` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `O__Etage neu` int(11) DEFAULT NULL,
  `P__Raum-Nr. neu (PPD-Nr.)` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `Q__Raum-Nr. neu (Raum-ID)` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `R__AP-Nr. neu` int(11) DEFAULT NULL,
  `S__a1` int(11) DEFAULT NULL,
  `T__Anzahl AP im Quell-Raum` int(11) DEFAULT NULL,
  `U__a3` int(11) DEFAULT NULL,
  `V__Name UZK` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `W__Nachname MA / Raumbezeichung` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `X__Vorname MA` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `Y__Titel` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `Z__Tel. MA` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AA_a4` int(11) DEFAULT NULL,
  `AB_Kürzel` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `AC_Code` int(11) DEFAULT NULL,
  `AD_Anzahl` int(11) DEFAULT NULL,
  `AE_Bezeichnung` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AF_Cluster Bezeichnung` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AG_B` int(11) DEFAULT NULL,
  `AH_T` int(11) DEFAULT NULL,
  `AI_H` int(11) DEFAULT NULL,
  `AJ_Volumen in cbm` decimal(10,4) DEFAULT NULL COMMENT 'Beim Export auf Umwandlung achten! (Zurück in deutsche Darstellung für Dezimalzeichen!)',
  `AK_Hersteller/` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AL_Modell/ Ausfuehrung/Material/Fuss/Form/hv/Fuss /Türart/Anzahl` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AL_Modell/ Ausfuehrung/Material/Fuss/Form/hv/Fuss /Türart/Anzahl Ablagen u.a.',
  `AM_Sitz-Lehne-Bezug/Polsterung/Sockel/u.a.` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AM_Sitz-Lehne-Bezug/Polsterung/Sockel/u.a.',
  `AN_Farbe_Sitz-Lehne_Bezug/Material_Platte/Farbe_Deckplatte/Farbe` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AN_Farbe_Sitz-Lehne_Bezug/Material_Platte/Farbe_Deckplatte/Farbe_Schirm/Form/Farbe_Oberfläche u.a.',
  `AO_Rechnername/Zollgroesse/Druckername/Montageart/Farbe_Korpus/F` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AO_Rechnername/Zollgroesse/Druckername/Montageart/Farbe_Korpus/Farbe_Platte',
  `AP_Seitenverh./Tel-Fax-Nr/Farbe_Fuss_/Farbe_Gestell/Farbe_Front/` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AP_Seitenverh./Tel-Fax-Nr/Farbe_Fuss_/Farbe_Gestell/Farbe_Front/Farbe_Tuer',
  `AQ_Weitere Eigenschaft/Rollentyp/drehbar/stapelbar/Rahmenausfueh` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT 'AQ_Weitere Eigenschaft/Rollentyp/drehbar/stapelbar/Rahmenausfuehrung/ Links-Rechts/ Winkel/Umleimer-Kante',
  `AR_Zustand` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `AS_Umzug (1/0)` int(1) DEFAULT NULL,
  `AT_De(na) Remon(na)tage erfor(na)derlich` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `AU_Inventar-Nr.` int(11) DEFAULT NULL,
  `AV_Bemerkungen` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AW_Barcode` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AX_Bild Nr 1` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AY_Bild Nr 2` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `AZ_Bild Nr 3` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `BA_Bild Nr 4` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `BB_Bild Nr 5` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `BC_Bild Nr 6` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `BD_Skizze Nr.` int(11) DEFAULT NULL,
  `BE` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Leer in CSV, muss drin sein damit Anzahl der Spalten überall gleich',
  `BF` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Leer in CSV, muss drin sein damit Anzahl der Spalten überall gleich',
  `BG` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Leer in CSV, muss drin sein damit Anzahl der Spalten überall gleich'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1000000 ;

--
-- Trigger `data_import`
--
DELIMITER //
CREATE TRIGGER `trig_import_items` AFTER INSERT ON `data_import`
 FOR EACH ROW BEGIN
DECLARE i INT DEFAULT 1;
DECLARE var_item_type INT DEFAULT 1;

-- Item-Typ ermitteln (HIER NEUE Item-Typen eintragen!!)
IF (NEW.AE_Bezeichnung LIKE '%Stuhl%') THEN
	SET var_item_type = 2;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Tisch%') THEN
	SET var_item_type = 3;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Schrank%') THEN
	SET var_item_type = 4;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Beamer%') THEN
	SET var_item_type = 5;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Drehstuhl%') OR (NEW.AE_Bezeichnung LIKE '%Bürostuhl%') THEN
	SET var_item_type = 6;
ELSEIF ((NEW.AE_Bezeichnung LIKE '%Bildschirm%') OR (NEW.AE_Bezeichnung LIKE '%TV%')) AND (NEW.AE_Bezeichnung NOT LIKE '%Wagen%') AND (NEW.AE_Bezeichnung NOT LIKE '%Gestell%') THEN
	SET var_item_type = 7;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Papierkorb%') OR (NEW.AE_Bezeichnung LIKE '%Müll%') THEN
	SET var_item_type = 8;
ELSEIF ((NEW.AE_Bezeichnung LIKE '%Tageslichtprojektor%') OR (NEW.AE_Bezeichnung LIKE '%OHP%') ) AND (NEW.AE_Bezeichnung NOT LIKE '%Wagen%') AND (NEW.AE_Bezeichnung NOT LIKE '%Gestell%') THEN
	SET var_item_type = 9;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Rollcontainer%') THEN
	SET var_item_type = 10;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Tafel%') THEN
	SET var_item_type = 11;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Telefon%') THEN
	SET var_item_type = 12;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Wanne%') THEN
	SET var_item_type = 13;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Tresor%') THEN
	SET var_item_type = 14;
END IF;

-- Fachbereich einfügen
IF NOT EXISTS(SELECT department_id FROM departments WHERE department_name = NEW.`D__Dezernat\/Fachbereich`) THEN
	INSERT INTO departments
	VALUES (NULL, NEW.`D__Dezernat\/Fachbereich`);
END IF;

-- Gebäude/Trakt einfügen
IF NOT EXISTS(SELECT building_id FROM buildings WHERE building_name = NEW.`H__Bauteil-Nr. Bestand`) THEN
	INSERT INTO buildings
    VALUES(NULL, NEW.`H__Bauteil-Nr. Bestand`, 1);
END IF;

-- Map einfügen
IF NOT EXISTS(SELECT map_id FROM maps WHERE map_building_id = (SELECT building_id FROM buildings WHERE building_name = NEW.`H__Bauteil-Nr. Bestand`) AND map_floor = NEW.`I__Etage Bestand`) THEN
	INSERT INTO maps
    VALUES (NULL, (SELECT building_id FROM buildings WHERE building_name = NEW.`H__Bauteil-Nr. Bestand`), NEW.`I__Etage Bestand`, NULL, NULL, NULL);
END IF;

-- Raum einfügen
IF NOT EXISTS(SELECT room_id FROM rooms WHERE room_name = NEW.`J__Raum-Nr. Bestand`) THEN
	INSERT INTO rooms
    VALUES(NULL, NEW.`J__Raum-Nr. Bestand`, NULL, NULL, NULL, NULL, NULL, (SELECT map_id FROM maps WHERE map_building_id = (SELECT building_id FROM buildings WHERE building_name = NEW.`H__Bauteil-Nr. Bestand`) AND map_floor = NEW.`I__Etage Bestand`), 1);
END IF;

-- Item n-mal einfügen
WHILE (i <= NEW.`AD_Anzahl`) DO
	INSERT INTO items
	VALUES (NULL, NEW.B__Index, (SELECT room_id FROM rooms WHERE room_name = NEW.`J__Raum-Nr. Bestand`), (SELECT department_id FROM departments WHERE department_name = NEW.`D__Dezernat\/Fachbereich`), NEW.AE_Bezeichnung, (SELECT room_id FROM rooms WHERE room_name = NEW.`J__Raum-Nr. Bestand`), NULL, NULL, NEW.AG_B, NEW.AH_T, NEW.AI_H, 0, NEW.AR_Zustand, var_item_type);
	SET i = i + 1;
END WHILE;


END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `trig_import_items_update` AFTER UPDATE ON `data_import`
 FOR EACH ROW BEGIN
DECLARE i INT DEFAULT 1;
DECLARE var_item_type INT DEFAULT 1;

-- Item-Typ ermitteln (HIER NEUE Item-Typen eintragen!!)
IF (NEW.AE_Bezeichnung LIKE '%Stuhl%') THEN
	SET var_item_type = 2;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Tisch%') THEN
	SET var_item_type = 3;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Schrank%') THEN
	SET var_item_type = 4;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Beamer%') THEN
	SET var_item_type = 5;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Drehstuhl%') OR (NEW.AE_Bezeichnung LIKE '%Bürostuhl%') THEN
	SET var_item_type = 6;
ELSEIF ((NEW.AE_Bezeichnung LIKE '%Bildschirm%') OR (NEW.AE_Bezeichnung LIKE '%TV%')) AND (NEW.AE_Bezeichnung NOT LIKE '%Wagen%') AND (NEW.AE_Bezeichnung NOT LIKE '%Gestell%') THEN
	SET var_item_type = 7;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Papierkorb%') OR (NEW.AE_Bezeichnung LIKE '%Müll%') THEN
	SET var_item_type = 8;
ELSEIF ((NEW.AE_Bezeichnung LIKE '%Tageslichtprojektor%') OR (NEW.AE_Bezeichnung LIKE '%OHP%') ) AND (NEW.AE_Bezeichnung NOT LIKE '%Wagen%') AND (NEW.AE_Bezeichnung NOT LIKE '%Gestell%') THEN
	SET var_item_type = 9;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Rollcontainer%') THEN
	SET var_item_type = 10;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Tafel%') THEN
	SET var_item_type = 11;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Telefon%') THEN
	SET var_item_type = 12;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Wanne%') THEN
	SET var_item_type = 13;
ELSEIF (NEW.AE_Bezeichnung LIKE '%Tresor%') THEN
	SET var_item_type = 14;
END IF;


-- Item n-mal einfügen
WHILE (i <= (NEW.`AD_Anzahl` - (SELECT AD_Anzahl FROM data_import WHERE B__Index = NEW.B__Index))) DO
	INSERT INTO items
	VALUES (NULL, NEW.B__Index, (SELECT room_id FROM rooms WHERE room_name = NEW.`J__Raum-Nr. Bestand`), (SELECT department_id FROM departments WHERE department_name = NEW.`D__Dezernat\/Fachbereich`), NEW.AE_Bezeichnung, (SELECT room_id FROM rooms WHERE room_name = NEW.`J__Raum-Nr. Bestand`), NULL, NULL, NEW.AG_B, NEW.AH_T, NEW.AI_H, 0,NEW.AR_Zustand, var_item_type);
	SET i = i + 1;
END WHILE;

END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
`department_id` int(11) NOT NULL,
  `department_name` varchar(32) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `items`
--

CREATE TABLE IF NOT EXISTS `items` (
`item_id` int(32) NOT NULL,
  `item_import_id` int(11) NOT NULL,
  `item_origin_room` int(11) NOT NULL COMMENT 'Herkunftsraum (im Bestand) des Items, wird einmal geschrieben, danach nicht geändert',
  `item_department_id` int(11) NOT NULL,
  `item_description` varchar(128) CHARACTER SET utf8 NOT NULL,
  `item_room_id` int(11) NOT NULL,
  `item_position_x` double DEFAULT NULL,
  `item_position_y` double DEFAULT NULL,
  `item_size_x` double DEFAULT NULL COMMENT 'Breite',
  `item_size_y` double DEFAULT NULL COMMENT 'Tiefe',
  `item_size_z` double DEFAULT NULL COMMENT 'Höhe',
  `item_orientation` int(11) NOT NULL DEFAULT '0' COMMENT 'Drehung des Items im Raum, 0-359 Grad',
  `item_state` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `item_type_id` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Trigger `items`
--
DELIMITER //
CREATE TRIGGER `trig_create_item` AFTER INSERT ON `items`
 FOR EACH ROW BEGIN

-- Items einfügen
IF NOT EXISTS(SELECT B__Index FROM data_export WHERE B__Index = NEW.item_import_id) THEN
	INSERT INTO data_export VALUES (
	    (SELECT `A__Zähler` FROM data_import WHERE NEW.item_import_id = B__Index),
    NEW.item_import_id,
    	(SELECT `C__Erfassungsdatum` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `D__Dezernat\/Fachbereich` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `E__Raumnutzungsart` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `F__Land-KZ` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `G__Liegenschafts-Nr. Bestand` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `H__Bauteil-Nr. Bestand` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `I__Etage Bestand` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `J__Raum-Nr. Bestand` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `K__AP-Nr. Bestand` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `L__a1` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `M__Liegenschafts-Nr. neu` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `N__Bauteil-Nr. neu` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `O__Etage neu` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `P__Raum-Nr. neu (PPD-Nr.)` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `Q__Raum-Nr. neu (Raum-ID)` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `R__AP-Nr. neu` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `S__a1` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `T__Anzahl AP im Quell-Raum` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT U__a3 FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `V__Name UZK` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `W__Nachname MA / Raumbezeichung` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `X__Vorname MA` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT Y__Titel FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `Z__Tel. MA` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT AA_a4 FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT AB_Kürzel FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT AC_Code FROM data_import WHERE NEW.item_import_id = B__Index),
    	1,
    	NEW.item_description,
    	(SELECT `AF_Cluster Bezeichnung` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT AG_B FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT AH_T FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT AI_H FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AJ_Volumen in cbm` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AK_Hersteller/` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AL_Modell/ Ausfuehrung/Material/Fuss/Form/hv/Fuss /Türart/Anzahl` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AM_Sitz-Lehne-Bezug/Polsterung/Sockel/u.a.` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AN_Farbe_Sitz-Lehne_Bezug/Material_Platte/Farbe_Deckplatte/Farbe` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AO_Rechnername/Zollgroesse/Druckername/Montageart/Farbe_Korpus/F` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AP_Seitenverh./Tel-Fax-Nr/Farbe_Fuss_/Farbe_Gestell/Farbe_Front/` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AQ_Weitere Eigenschaft/Rollentyp/drehbar/stapelbar/Rahmenausfueh` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AR_Zustand` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AS_Umzug (1/0)` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AT_De(na) Remon(na)tage erfor(na)derlich` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AU_Inventar-Nr.` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AV_Bemerkungen` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AW_Barcode` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AX_Bild Nr 1` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AY_Bild Nr 2` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `AZ_Bild Nr 3` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `BA_Bild Nr 4` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `BB_Bild Nr 5` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `BC_Bild Nr 6` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `BD_Skizze Nr.` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `BE` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `BF` FROM data_import WHERE NEW.item_import_id = B__Index),
    	(SELECT `BG` FROM data_import WHERE NEW.item_import_id = B__Index)
	);
ELSE
	UPDATE data_export
    SET `AD_Anzahl` = `AD_Anzahl` + 1
    WHERE `B__Index` = NEW.item_import_id;
END IF;

END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `trig_delete_item` BEFORE DELETE ON `items`
 FOR EACH ROW BEGIN

-- Aus ursprünglichem Datensatz entfernen
UPDATE data_export
SET `AJ_Volumen in cbm` = `AJ_Volumen in cbm` - `AJ_Volumen in cbm`/`AD_Anzahl`, AD_Anzahl = AD_Anzahl - 1
WHERE OLD.item_import_id = `B__Index`
  AND (SELECT department_name FROM departments WHERE department_id = OLD.item_department_id) = `D__Dezernat\/Fachbereich`
    AND OLD.item_description = `AE_Bezeichnung`
    AND OLD.item_state <=> `AR_Zustand`;

    -- Prüfen, ob der alte Datensatz noch Items enthält (Anzahl > 0)
IF EXISTS(SELECT * FROM data_export WHERE `AD_Anzahl` < 1) THEN
    DELETE FROM data_export WHERE `AD_Anzahl` < 1;
END IF;

END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `trig_update_item` AFTER UPDATE ON `items`
 FOR EACH ROW BEGIN

-- Volumen eines Exemplars abrufen
DECLARE var_vol_cbm decimal(10,4) DEFAULT 0;
SET var_vol_cbm = (SELECT `AJ_Volumen in cbm` FROM data_export WHERE NEW.item_import_id = `B__Index` AND (SELECT department_name FROM departments WHERE department_id = OLD.item_department_id) = `D__Dezernat\/Fachbereich` AND OLD.item_description = `AE_Bezeichnung` AND OLD.item_state = `AR_Zustand` AND (SELECT room_name FROM rooms WHERE room_id = OLD.item_room_id) = `J__Raum-Nr. Bestand` OR (SELECT room_name FROM rooms WHERE room_id = OLD.item_room_id) = `Q__Raum-Nr. neu (Raum-ID)`)/(SELECT `AD_Anzahl` FROM data_export WHERE NEW.item_import_id = `B__Index` AND (SELECT department_name FROM departments WHERE department_id = OLD.item_department_id) = `D__Dezernat\/Fachbereich` AND OLD.item_description = `AE_Bezeichnung` AND OLD.item_state = `AR_Zustand` AND (SELECT room_name FROM rooms WHERE room_id = OLD.item_room_id) = `J__Raum-Nr. Bestand` OR (SELECT room_name FROM rooms WHERE room_id = OLD.item_room_id) = `Q__Raum-Nr. neu (Raum-ID)`);

-- Fachbereich, Bezeichnung, Zustand oder Raum eines Items geändert?
IF NEW.item_department_id != OLD.item_department_id OR NEW.item_description != OLD.item_description OR NEW.item_state != OLD.item_state OR NEW.item_room_id != OLD.item_room_id THEN

  -- Aus ursprünglichem Datensatz entfernen
    UPDATE data_export
    SET `AJ_Volumen in cbm` = `AJ_volumen in cbm` - var_vol_cbm, AD_Anzahl = AD_Anzahl - 1
    WHERE NEW.item_import_id = `B__Index`
      AND (SELECT department_name FROM departments WHERE department_id = OLD.item_department_id) = `D__Dezernat\/Fachbereich`
        AND OLD.item_description = `AE_Bezeichnung`
        AND (SELECT room_name FROM rooms WHERE room_id = OLD.item_room_id) = `J__Raum-Nr. Bestand`
        OR (SELECT room_name FROM rooms WHERE room_id = OLD.item_room_id) = `Q__Raum-Nr. neu (Raum-ID)`
        AND OLD.item_state <=> `AR_Zustand`;

    -- Prüfen, ob der alte Datensatz noch Items enthält (Anzahl > 0)
    IF EXISTS(SELECT * FROM data_export WHERE `AD_Anzahl` < 1) THEN
      DELETE FROM data_export WHERE `AD_Anzahl` < 1;
    END IF;

  -- Zu neuem Datensatz hinzufügen bzw. bei altem Datensatz Anzahl erhöhen
  IF NOT EXISTS(
        SELECT B__Index
        FROM data_export
        WHERE B__Index = NEW.item_import_id
          AND `D__Dezernat\/Fachbereich` = (SELECT department_name FROM departments WHERE department_id = NEW.item_department_id)
          AND `AE_Bezeichnung` = NEW.item_description
          AND `AR_Zustand` = NEW.item_state
          AND `J__Raum-Nr. Bestand` = (SELECT room_name FROM rooms WHERE room_id = NEW.item_room_id)
    ) THEN

        INSERT INTO data_export VALUES (
          (SELECT `A__Zähler` FROM data_import WHERE NEW.item_import_id = B__Index),
        NEW.item_import_id,
          (SELECT `C__Erfassungsdatum` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT department_name FROM departments WHERE department_id = NEW.item_department_id),
          (SELECT `E__Raumnutzungsart` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `F__Land-KZ` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `G__Liegenschafts-Nr. Bestand` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `H__Bauteil-Nr. Bestand` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `I__Etage Bestand` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `J__Raum-Nr. Bestand` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `K__AP-Nr. Bestand` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `L__a1` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `M__Liegenschafts-Nr. neu` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT building_name FROM buildings, maps, rooms WHERE room_map_id = map_id AND map_building_id = building_id AND room_id = NEW.item_room_id),
          (SELECT map_floor FROM maps, rooms WHERE room_map_id = map_id AND room_id = NEW.item_room_id),
          (SELECT room_name_alt FROM rooms WHERE room_id = NEW.item_room_id),
          (SELECT room_name FROM rooms WHERE room_id = NEW.item_room_id),
          (SELECT `R__AP-Nr. neu` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `S__a1` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `T__Anzahl AP im Quell-Raum` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `U__a3` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `V__Name UZK` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `W__Nachname MA / Raumbezeichung` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `X__Vorname MA` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `Y__Titel` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `Z__Tel. MA` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT AA_a4 FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT AB_Kürzel FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT AC_Code FROM data_import WHERE NEW.item_import_id = B__Index),
          1,
          NEW.item_description,
          (SELECT `AF_Cluster Bezeichnung` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT AG_B FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT AH_T FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT AI_H FROM data_import WHERE NEW.item_import_id = B__Index),
          (var_vol_cbm), -- Volumen für ein Exemplar
          (SELECT `AK_Hersteller/` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AL_Modell/ Ausfuehrung/Material/Fuss/Form/hv/Fuss /Türart/Anzahl` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AM_Sitz-Lehne-Bezug/Polsterung/Sockel/u.a.` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AN_Farbe_Sitz-Lehne_Bezug/Material_Platte/Farbe_Deckplatte/Farbe` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AO_Rechnername/Zollgroesse/Druckername/Montageart/Farbe_Korpus/F` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AP_Seitenverh./Tel-Fax-Nr/Farbe_Fuss_/Farbe_Gestell/Farbe_Front/` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AQ_Weitere Eigenschaft/Rollentyp/drehbar/stapelbar/Rahmenausfueh` FROM data_import WHERE NEW.item_import_id = B__Index),
          NEW.item_state,
          (SELECT `AS_Umzug (1/0)` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AT_De(na) Remon(na)tage erfor(na)derlich` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AU_Inventar-Nr.` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AV_Bemerkungen` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AW_Barcode` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AX_Bild Nr 1` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AY_Bild Nr 2` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `AZ_Bild Nr 3` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `BA_Bild Nr 4` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `BB_Bild Nr 5` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `BC_Bild Nr 6` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `BD_Skizze Nr.` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `BE` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `BF` FROM data_import WHERE NEW.item_import_id = B__Index),
          (SELECT `BG` FROM data_import WHERE NEW.item_import_id = B__Index)
      );

    ELSE
      UPDATE data_export
      SET `AJ_Volumen in cbm` = `AJ_volumen in cbm` + var_vol_cbm,`AD_Anzahl` = `AD_Anzahl` + 1
      WHERE `B__Index` = NEW.item_import_id
          AND `D__Dezernat\/Fachbereich` = (SELECT department_name FROM departments WHERE department_id = NEW.item_department_id)
            AND `AE_Bezeichnung` = NEW.item_description
            AND `AR_Zustand` = NEW.item_state
            AND `J__Raum-Nr. Bestand` = (SELECT room_name FROM rooms WHERE room_id = NEW.item_room_id);
    END IF;

END IF;

END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `item_types`
--

CREATE TABLE IF NOT EXISTS `item_types` (
`item_type_id` int(2) NOT NULL,
  `item_type_name` varchar(32) CHARACTER SET utf8 NOT NULL,
  `item_type_picture` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Daten für Tabelle `item_types`
--

INSERT INTO `item_types` (`item_type_id`, `item_type_name`, `item_type_picture`) VALUES
(1, 'Sonstige', 'img/item-types/sonstige.svg'),
(2, 'Stuhl', 'img/item-types/stuhl.svg'),
(3, 'Tisch', 'img/item-types/tisch.svg'),
(4, 'Schrank', 'img/item-types/schrank.svg'),
(5, 'Beamer', 'img/item-types/beamer.svg'),
(6, 'Drehstuhl', 'img/item-types/drehstuhl.svg'),
(7, 'Bildschirm', 'img/item-types/bildschirm.svg'),
(8, 'Mülleimer', 'img/item-types/muell.svg'),
(9, 'OHP', 'img/item-types/ohp.svg'),
(10, 'Rollcontainer', '/img/item-types/rollcontainer.svg'),
(11, 'Tafel', 'img/item-types/tafel.svg'),
(12, 'Telefon', 'img/item-types/telefon.svg'),
(13, 'Wanne', 'img/item-types/wanne.svg'),
(14, 'Tresor', 'img/item-types/tresor.svg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `maps`
--

CREATE TABLE IF NOT EXISTS `maps` (
`map_id` int(11) NOT NULL,
  `map_building_id` int(11) NOT NULL,
  `map_floor` int(11) NOT NULL,
  `map_picture` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `map_scale_cm` int(11) unsigned DEFAULT NULL,
  `map_scale_px` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
`role_id` int(32) NOT NULL,
  `role_name` varchar(32) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(0, 'gesperrt'),
(1, 'Benutzer'),
(2, 'Administrator');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
`room_id` int(32) unsigned NOT NULL,
  `room_name` varchar(11) CHARACTER SET utf8 NOT NULL COMMENT 'Für Bestand bspw. H1.17, für Neubau Raum-ID (bspw. 04.1.002)',
  `room_name_alt` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Nur Neubau! PPD-Nr., bspw. 19',
  `room_position_x` int(32) DEFAULT NULL,
  `room_position_y` int(32) DEFAULT NULL,
  `room_size_x` int(32) DEFAULT NULL,
  `room_size_y` int(32) DEFAULT NULL,
  `room_map_id` int(32) DEFAULT NULL,
  `room_type` tinyint(1) NOT NULL COMMENT '0 für virtuell (Wunschliste, Müll, Lager),1 für Bestand, 2 für Neubau'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`user_id` int(11) NOT NULL,
  `user_name` varchar(32) CHARACTER SET utf8 NOT NULL,
  `user_firstname` varchar(50) CHARACTER SET utf8 NOT NULL,
  `user_lastname` varchar(40) CHARACTER SET utf8 NOT NULL,
  `user_password` varchar(50) CHARACTER SET utf8 NOT NULL,
  `user_email` varchar(32) CHARACTER SET utf8 NOT NULL,
  `user_role_id` tinyint(1) NOT NULL,
  `user_active` tinyint(1) NOT NULL,
  `user_secure_code` varchar(42) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Trigger `users`
--
DELIMITER //
CREATE TRIGGER `trig_delete_user` BEFORE DELETE ON `users`
 FOR EACH ROW BEGIN
DECLARE var_finished INT DEFAULT 0;
DECLARE var_item_id INT;
DECLARE item_cursor CURSOR FOR SELECT item_id FROM `items` WHERE `item_room_id` IN (
    SELECT `room_id`
    FROM `rooms`
    WHERE `room_name` = CONCAT('store_', OLD.user_id)
    OR `room_name` = CONCAT('trash_', OLD.user_id)
    OR `room_name` = CONCAT('wishlist_', OLD.user_id)
    );
DECLARE CONTINUE HANDLER FOR NOT FOUND SET var_finished = 1;

-- Alle vorhandenen Items zurück in den ursprünglichen Raum schieben
OPEN item_cursor;

loop_items: LOOP
	FETCH item_cursor INTO var_item_id;
    IF var_finished = 1 THEN
    	LEAVE loop_items;
    END IF;

    UPDATE `items`
    SET `item_room_id` = `item_origin_room`
    WHERE `item_id` = var_item_id;

END LOOP loop_items;

-- Lager, Wunschliste und Müll löschen
DELETE FROM `user_role_room`
WHERE `role_room_user_id` = OLD.`user_id`
AND `role_room_room_id` IN (
    SELECT `room_id`
    FROM `rooms`
    WHERE `room_name` = CONCAT('store_', OLD.`user_id`)
	OR `room_name` = CONCAT('trash_', OLD.`user_id`)
	OR `room_name` = CONCAT('wishlist_', OLD.`user_id`)
    OR `room_name` = 'store_all');

DELETE FROM `rooms`
WHERE `room_name` = CONCAT('store_', OLD.`user_id`)
OR `room_name` = CONCAT('trash_', OLD.`user_id`)
OR `room_name` = CONCAT('wishlist_', OLD.`user_id`);

END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `trig_insert_user` AFTER INSERT ON `users`
 FOR EACH ROW BEGIN

-- Falls nicht existent, Lager für alle Nutzer erstellen
IF NOT EXISTS(SELECT * FROM `rooms` WHERE `room_name` = 'store_all') THEN
	INSERT INTO `rooms` VALUES(NULL, 'store_all', NULL, NULL, NULL, NULL, NULL, NULL, 0);
END IF;

-- Allgemeinen Lagerraum zugänglich machen
INSERT INTO `user_role_room` VALUES (NEW.user_id, (SELECT room_id FROM rooms WHERE room_name = 'store_all'), 1);

-- Lagerraum anlegen
INSERT INTO `rooms` VALUES (NULL, CONCAT('store_', NEW.user_id), NULL, NULL, NULL, NULL, NULL, NULL, 0);

INSERT INTO `user_role_room` VALUES (NEW.user_id, (SELECT room_id FROM rooms WHERE room_name = CONCAT('store_', NEW.user_id)), 1);

-- Wunschliste anlegen
INSERT INTO `rooms` VALUES (NULL, CONCAT('wishlist_', NEW.user_id), NULL, NULL, NULL, NULL, NULL, NULL, 0);

INSERT INTO `user_role_room` VALUES (NEW.user_id, (SELECT room_id FROM rooms WHERE room_name = CONCAT('wishlist_', NEW.user_id)), 1);

-- Müll anlegen
INSERT INTO `rooms` VALUES (NULL, CONCAT('trash_', NEW.user_id), NULL, NULL, NULL, NULL, NULL, NULL, 0);

INSERT INTO `user_role_room` VALUES (NEW.user_id, (SELECT room_id FROM rooms WHERE room_name = CONCAT('trash_', NEW.user_id)), 1);


END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_role_room`
--

CREATE TABLE IF NOT EXISTS `user_role_room` (
  `role_room_user_id` int(11) unsigned NOT NULL,
  `role_room_room_id` int(11) unsigned NOT NULL,
  `role_room_role_id` int(1) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
 ADD PRIMARY KEY (`building_id`);

--
-- Indexes for table `data_import`
--
ALTER TABLE `data_import`
 ADD PRIMARY KEY (`B__Index`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
 ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
 ADD PRIMARY KEY (`item_id`), ADD KEY `item_import_id` (`item_import_id`), ADD KEY `item_department_id` (`item_department_id`);

--
-- Indexes for table `item_types`
--
ALTER TABLE `item_types`
 ADD PRIMARY KEY (`item_type_id`);

--
-- Indexes for table `maps`
--
ALTER TABLE `maps`
 ADD PRIMARY KEY (`map_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
 ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
 ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_role_room`
--
ALTER TABLE `user_role_room`
 ADD PRIMARY KEY (`role_room_user_id`,`role_room_room_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
MODIFY `building_id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `data_import`
--
ALTER TABLE `data_import`
MODIFY `B__Index` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1000000;
--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
MODIFY `item_id` int(32) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_types`
--
ALTER TABLE `item_types`
MODIFY `item_type_id` int(2) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `maps`
--
ALTER TABLE `maps`
MODIFY `map_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
MODIFY `role_id` int(32) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
MODIFY `room_id` int(32) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
