#MoveIT

#####Grundstruktur
- admin - Adminbereich
- css - Alle CSS Dateien (Alles was auf .css endet)
- js - Alle Javascript Dateien (Alles was auf .js endet (jquery.min.js, jquery.js))
- include - Alle Dateien die via PHP eingebunden werden
- img - Bilder für das Design (z. B. Hintergründe)

#####Upload-Struktur
 - csv   - Die CSV -Dateien für den Admin
 - items
   - icon   - Icons der einzelnen Möbelstücke (drag & drop)
   - photos - Fotos der Möbelstücke
 - maps  - Karten der FH


#####To-Do
- Drag'n'Drop
    - Aus Neubau in Altbau oder Lager, Müll, usw. schieben
    - Draggable-Bereiche definieren
    - Items in richtiger Größe einfügen
    - (Items im Kartenbereich brauchen Popup mit weiteren Informationen)
    - Neue Item-Positionen in DB schreiben und bestehende auslesen
        - Momentan wird alter Raum geschrieben (Problem ist wahrscheinlich die globale Room-Variable)
- Mapdialog fertigstellen
    - (Map-Layer auf Karte aufsetzen, wie wurde das im Map-Editor gemacht?)
    - (Auswahlfelder fixen)

- Demo-Installation für Präsentation aufsetzen
- Neuen DB-Export fertig machen, mit erstem User
