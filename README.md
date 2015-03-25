#MoveIT

#####Installationsanleitung
Zum Betrieb von moveIT wird ein Server mit Apache, PHP und MySQL-Installation benötigt.

Um moveIT einzurichten, bitte diesen Ordner auf den Server verschieben.
Zudem muss die Datei unter /_CONFIG in das Datenbanksystem importiert werden, dies geschieht am Einfachsten mithilfe der Importfunktion von PHPmyadmin. Der Import kann nur erflogreich durchgeführt werden, wenn Rechte zum Anlegen von Prozeduren und Triggern vorliegen. Anschließend müssen in der Datei /include/classes/settings.ini.php die der Datenbank entsprechenden Einstellungen vorgenommen werden.

Abschließend kann man sich mit dem Account "moveit-admin@fh-duesseldorf.de" und dem Passwort "1234" auf der Seite anmelden. Bitte danach auf jeden Fall das Passwort des Benutzers unter "MoveIT Admin" (oben rechts) -> "Administration" -> "Benutzer" ändern, um Sicherheitsrisiken zu vermeiden!

Nun können bestehende Daten des Möbelspediteurs importiert werden. Gegebenenfalls ist zuvor eine Umwandlung der gegebenen .xls-Datei in das .csv-Format vonnöten. Am Einfachsten gestaltet sich dies, indem die .xls-Datei mit Excel geöffnet wird und mit dem Dateityp "CSV (Trennzeichen-getrennt) (*.csv)" gespeichert wird.

Nun können weitere Gebäude, Maps (Gebäude x Etagen) und Räume angelegt werden. Zu Maps im Neubau lassen sich zudem Übersichtskarten anhängen und Räume positionieren.

Sobald weitere Nutzer an MoveIT teilnehmen, hat man als Administrator die Möglichkeit, ihnen Räume zuzuweisen, in denen sie über Bearbeitungsrechte verfügen. Diese Räume sind daraufhin für diese Nutzer sichtbar und Möbelstücke können aus ihnen verschoben und neu zugewiesen werden.

#####To-Do
- Drag'n'Drop
    - Von Überall nach Überall schieben
    - Draggable-Bereiche definieren
    - Items im Kartenbereich brauchen Popup mit weiteren Informationen
    - (Item Z-Werte speichern/laden)
    - Tooltips für die Items
        - Erledigt! -> Umstylen?
- Mapdialog fertigstellen
    - (Map-Layer auf Karte aufsetzen, wie wurde das im Map-Editor gemacht?)
    - (Auswahlfelder fixen)
- Raumerstellung
    - Wenn Raum in Neubau platziert -> Hinweis (mit Link?) auf Mapbearbeitung
- Dropdown
    - Nur Gebäude und jeweilige Etagen darstellen, in denen Bearbeitungsrechte vorliegen (weitere API)
- Icons
    - Die Icons im Planer haben momentan einen zu großen inneren Rand
- Import/Export
    - Hinweis für Verhalten bei aufeinanderfolgenden Uploads

- Trigger
    - Item-Anzahl in Export-Tabelle wird nicht richtig aktualisiert!! (SHOWSTOPPER)

- Demo-Installation für Präsentation aufsetzen
