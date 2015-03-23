var BASEURL = '/moveit/';
var roomId; // globale Variable für den ausgwählten Raum

$(document).ready(function () {
    //An jedes Formular einen Token heften
    $('form').prepend('<input type="hidden" name="token" value="' + mainSettings.csrfToken + '"/>');
    $('.logout').attr('href', BASEURL + 'logout/' + mainSettings.csrfToken);


    //Laden der Itemtypen
    $.ajax({
        type: 'POST',
        url: BASEURL + 'api/getItemTypes',
        dataType: 'json',
        success: function (data) {
            itemTypes = data.types;
        }/*,
        error: function(){
            console.log("retry");
            setTimeout(function(){$.ajax(this)}, 7000);
        }*/
    });

    // Laden von Lager, Müll und öffentlichem Lager
    getItemsVirtualRooms('#LagerListe', 'api/getItems/store/user');
    getItemsVirtualRooms('#MuellListe', 'api/getItems/trash');
    getItemsVirtualRooms('#oeffentlichesLagerListe', 'api/getItems/store/all');

    // Neulade-Button
    $("#btnOeffReset").click(function(){
        getItemsVirtualRooms('#oeffentlichesLagerListe', 'api/getItems/store/all');
    });


    //Popup vor dem Löschvorgang
    $('body').on('click', '.delete-button, .reset-database', function () {
        var el = $(this);
        bootbox.confirm("Wollen Sie den Eintrag wirklich löschen?", function (result) {
            if (result) {
                location.href = el.attr('href');
            }
        });
        return false;
    });


    $("#ObereLeiste").tabs({
        heightStyle: "content"
    });

    $(function () {
        $("#akkordeon").accordion({
            collapsible: true,
            heightStyle: "content"
        });
    });

    // Selectmenue
    $("#AltbauTrakt").selectmenu();
    $("#AltbauEtage").selectmenu();
    $("#AltbauRaum").selectmenu();

    $("#NeubauTrakt").selectmenu();
    $("#NeubauEtage").selectmenu();
    $("#NeubauRaum").selectmenu();

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $("#Verwerfen").on("click", function () {
        confirm("Wollen Sie die Änderungen am Raum wirklich verwerfen?");
    });

    $("#MuellButton").button({
        icons: {
            primary: "ui-icon-trash"
        },
        text: false
    });



    // Dialog-Fenster für Neubau-Grundriss
    $("#dialog-GrundrissNeubau").dialog({
        autoOpen: false,
        height: 600,
        width: 700,

        modal: true,
    });

    $("#mapEditorDialog").dialog({
        autoOpen: false,
        height: 400,
        width: 700,
        modal: true,
    });

    $("#helpButton").click(function () {
        $("#mapEditorDialog").dialog("open");
    });    

    $("#GrundrissNeubau").click(function () {
        $("#dialog-GrundrissNeubau").dialog("open");

        $("#NeubauTraktMap").selectmenu();
        $("#NeubauEtageMap").selectmenu();
    });



    // Dialog-Fenster für Altbau-Grundriss
    $("#dialog-GrundrissAltbau").dialog({
        autoOpen: false,
        height: 600,
        width: 700,
        modal: true,
    });

    $("#GrundrissAltbau").click(function () {
        $("#dialog-GrundrissAltbau").dialog("open");
    });


    //Dialog für die Wunschliste
    var form, dialog;
    var bezeichnung = $("#wunschdialogform #bezeichnung");
    var anzahl = $("#wunschdialogform #anzahl");
    var länge = $("#wunschdialogform #länge");
    var breite = $("#wunschdialogform #breite");
    var allFields = $([]).add(bezeichnung).add(anzahl).add(länge).add(breite);
    var tips = $(".validateTips");

    var bezeichnung2 = $("#dialog-Itembearbeiten #bezeichnung");
    var anzahl2 = $("#dialog-Itembearbeiten #anzahl");
    var länge2 = $("#dialog-Itembearbeiten #länge");
    var breite2 = $("#dialog-Itembearbeiten #breite");
    var allFields2 = $([]).add(bezeichnung2).add(anzahl2).add(länge2).add(breite2);

    var listenButtongeklickt,
        wirklichHinzugefügt;

    $("#Wunschhinzufügen").button({});

    //Wunsch Button
    $("#Wunschhinzufügen").click(function () {
        dialog.dialog("open");
    });
    //Dialog eigenschaften der Wunschliste
    dialog = $("#wunschdialogform").dialog({
        autoOpen: false,
        height: 500,
        width: 400,
        modal: true,
        buttons: {
            "Item erstellen": function () {
                addItem();
                if (wirklichHinzugefügt == true) {
                    $("<button></button>")
                        .attr('id', 'Itembearbeiten')
                        .appendTo('#wunschtabelle tbody tr:last-child td:first-child')
                        .button({
                            icons: {
                                primary: "ui-icon-gear"
                            },
                            text: false
                        })
                        .on("click", function () {
                            listenButtongeklickt = this;
                            dialogBearbeiten.dialog("open");
                        });

                    $("<button></button>")
                        .attr('id', 'Itemlöschen')
                        .appendTo('#wunschtabelle tbody tr:last-child td:first-child')
                        .button({
                            icons: {
                                primary: "ui-icon-trash"
                            },
                            text: false
                        })
                        .on("click", function () {
                            listenButtongeklickt = this;
                            dialogLoeschen.dialog("open");
                        });
                }
            },
            Abbrechen: function () {
                updateTips("");
                dialog.dialog("close");
            }
        },
        close: function () {
            form[0].reset();
            allFields.removeClass("ui-state-error");
            updateTips("");
        }
    });



    form = dialog.find("form").on("submit", function (event) {
        event.preventDefault();
        addItem();
    });

    /* Item LÖSCHEN ////////////////////////////////////////////////////////////////////////////////////*/

    dialogLoeschen = $("#dialog-Itemlöschen").dialog({
        autoOpen: false,
        buttons: {
            "Löschen": function () {
                var zulöschendeZeile = $(listenButtongeklickt.parentElement.parentElement).attr("id");
                $("#" + zulöschendeZeile + "").remove();
                $(this).dialog("close");
            },
            Abbrechen: function () {
                $(this).dialog("close");
            }
        }
    });




    //DRAG UND DROP
    // Funktion um Items zwischen Listen bewegen zu können

    $("#LagerTab, #WunschTab, #MüllTab").tabs().find("#ObereLeiste").sortable({
        axis: "x"
    });

    $("#MuellListe, #oeffentlichesLagerListe, #LagerListe, #AltbauListe, #NeubauMap").sortable({
        connectWith: "#MuellListe, #oeffentlichesLagerListe, #LagerListe, #AltbauListe"
    }).disableSelection();
    // Tabs auf denen man die Elemente bewegen kann
    var $tabs = $("#ObereLeiste").tabs();
    var $tab_items = $("ul:first li", $tabs).droppable({
        // Festlegen das man nur Listenelemente bewegen kann
        accept: "div",
        hoverClass: "ui-state-hover",
        // Items auch auf leere Listen ablegen
        tolerance: "pointer",
        dropOnEmpty: true,
        drop: function (event, ui) {
            var $item = $(this);
            // Tabs auf denen man die Elemente bewegen kann festlegen
            var $list = $($item.find("a").attr("href")).find("#MuellListe, #oeffentlichesLagerListe, #LagerListe, #AltbauListe");
            ui.draggable.hide("slow", function () {
                $(this).appendTo($list).show("slow");
            });
        }
    });

    //für die NeubauMap
    $("#NeubauMap").droppable({
        dropOnEmpty: true,
        tolerance: "fit",
        stop: function (event, ui) {

        },
        drop: function (event, ui) {
            // Attribut 'data-type' des gedroppten Items auslesen
            var dataImg = $(ui.draggable).data("img");
            var dataId = $(ui.draggable).data("item-id");
            var dataTitle = $(ui.draggable).data("title");

            var gedroptesItem = $(ui.draggable);

            // Position auslesen um diese dem IMG geben zu können, damit IMG dort erscheint, wo gedroppt wird
            var gedroptesItemPosition = gedroptesItem.offset();

            $("#NeubauMap").append('<img data-title="' + dataTitle + '" data-img="' + dataImg + '" data-item-id="' + dataId + '" class="planner-item-' + dataId + '" src="' + dataImg + '">'); // class Attribut vergeben, um Items mit diesem Attribut draggable machen zu können


            $('[class^="planner-item-"]').draggable();


            $('.planner-item-' + dataId).css({
                'position': 'absolute',
                'top': gedroptesItem.top,
                'left': gedroptesItem.left,
                'z-index': 4
            });

            $(gedroptesItem).remove();
            //dragAndDrop(); // für alle Elemente mit class='moveitplaner'

        }
    });


    $('#AltbauListe').droppable({
        accept: '.moveitplaner',
        tolerance: 'fit',
        drop: function () {
            alert('ToDO: Umwandeln in ListenElement für die AltBauListe');
        }
    });

    dragAndDrop(); // macht Icons draggable
    $(".moveitplaner").on("dblclick", rotate);

    // rotiert Element von seinem Ausgangspunkt aus um 90 Grad. Setzt entsprechende Gradzahl (0, 90,..., 270) der Rotation in Hilfsattribut 'rotation-value'
    function rotate() {
        var rotation = 0; // lokale Variable

        var rotationValue = parseInt($(this).attr("rotation-value")); // aktuellen Wert des Hilfs-Attributs 'rotation-value' auslesen

        if (rotationValue > 0) { // falls Wert für Hilfs-Attribut existiert (sonst NaN) und größer 0 ist, diesen für die nächste 90 Grad-Rotation als Ausgangswert nehmen
            rotation = rotationValue + 90;
        } else { // sonst um 90 Grad drehen
            rotation = 90;
        }

        if (rotation == 360) { // werden für die Rotation 360 Grad (volle Drehung) erreicht, wird der Wert auf 0 Grad zurückgesetzt
            rotation = 0;
        }

        $(this).css("transform", "rotate(" + rotation + "deg)").attr("rotation-value", rotation); // Hilfs-Attribut setzen
    };



    // Lade Items zum ersten mal
    getItems(roomId);




    /* Item BEARBEITEN /////////////////////////////////////////////////////////////////////////////////////////// */

    dialogBearbeiten = $("#dialog-Itembearbeiten").dialog({
        autoOpen: false,
        height: 500,
        width: 400,
        modal: true,
        open: function () {

            var zeileÄndern = $(listenButtongeklickt.parentElement.parentElement).attr("id");

            $(bezeichnung2).val($("#" + zeileÄndern + " td:nth-child(2)").text());
            $(anzahl2).val($("#" + zeileÄndern + " td:nth-child(3)").text());
            $(länge2).val($("#" + zeileÄndern + " td:nth-child(4)").text());
            $(breite2).val($("#" + zeileÄndern + " td:nth-child(5)").text());
        },
        buttons: {
            Speichern: function () {
                eintragErsetzen();
            },
            Verwerfen: function () {
                updateTips("");
                $(this).dialog("close");
            }
        },
        close: function () {
            form2[0].reset();
            allFields2.removeClass("ui-state-error");
            updateTips("");

        }
    });

    form2 = dialogBearbeiten.find("form").on("submit", function (event) {
        event.preventDefault();
        eintragErsetzen();
    });
});

function addItem() {
    allFields.removeClass("ui-state-error");
    var _valid = valid(bezeichnung, anzahl, länge, breite);
    if (_valid) {
        $("#wunschtabelle tbody").append("<tr id=" + bezeichnung.val() + ">" +
            "<td>" + "</td>" +
            "<td>" + bezeichnung.val() + "</td>" +
            "<td>" + anzahl.val() + "</td>" +
            "<td>" + länge.val() + "</td>" +
            "<td>" + breite.val() + "</td>" +
            "</tr>");
        dialog.dialog("close");
        updateTips("");
    }
    wirklichHinzugefügt = _valid;
    return _valid;
}

function eintragErsetzen() {
    allFields2.removeClass("ui-state-error");
    var _valid = valid(bezeichnung2, anzahl2, länge2, breite2);
    var zeileÄndern = $(listenButtongeklickt.parentElement.parentElement).attr("id");
    if (_valid) {

        $("#" + zeileÄndern + " td:nth-child(2)").replaceWith("<td>" + bezeichnung2.val() + "</td>");
        $("#" + zeileÄndern + " td:nth-child(3)").replaceWith("<td>" + anzahl2.val() + "</td>");
        $("#" + zeileÄndern + " td:nth-child(4)").replaceWith("<td>" + länge2.val() + "</td>");
        $("#" + zeileÄndern + " td:nth-child(5)").replaceWith("<td>" + breite2.val() + "</td>");


        dialogBearbeiten.dialog("close");
        updateTips("");
    }
    return _valid;
}



/* HILFSKLASSEN/////////////////////////////////////////////////// */

function valid(_bezeichnung, _anzahl, _länge, _breite) {
    var valid = true;

    valid = valid && checkLength(_bezeichnung, "bezeichnung", 2, 20);
    valid = valid && checkLength(_anzahl, "anzahl", 1, 4);
    valid = valid && checkLength(_länge, "länge", 1, 10);
    valid = valid && checkLength(_breite, "breite", 1, 10);

    valid = valid && checkRegexp(_bezeichnung, /^[a-z]([0-9a-z_\s])+$/i, "Nur a-z, 0-9, Unterstriche, Leerzeichen und muss mit einem Buchstaben anfangen.");
    valid = valid && checkRegexp(_anzahl, /^([0-9])+$/, "Nur Zahlen erlaubt");
    valid = valid && checkRegexp(_länge, /^([0-9])+$/, "Nur Zahlen erlaubt");
    valid = valid && checkRegexp(_breite, /^([0-9])+$/, "Nur Zahlen erlaubt");


    return valid;

}

function updateTips(t) {
    tips.text(t).addClass("ui-state-highlight");
    setTimeout(function () {
        tips.removeClass("ui-state-highlight", 1500);
    }, 500);
}

function checkLength(o, n, min, max) {
    if (o.val().length > max || o.val().length < min) {
        o.addClass("ui-state-error");
        updateTips("Länge muss zwischen " + min + " und " + max + " betragen.");
        return false;
    } else {
        return true;
    }
}

function checkRegexp(o, regexp, n) {
    if (!(regexp.test(o.val()))) {
        o.addClass("ui-state-error");
        updateTips(n);
        return false;
    } else {
        return true;
    }
}


//Funktion für Drag and Drop der Icons (alle Elemente mit class='moveitplaner') in der NeubauMap
function dragAndDrop() {
    /*$('.moveitplaner').each(function () {
        $(this).draggable({ //alle Elemente mit class='moveitplaner' draggable machen;
            scroll: false,
            //revert: 'invalid',
            stop: function () {
                $(this).draggable('option', 'revert', 'invalid');
                $('#AltbauListe').css('border', ''); // Reset
            },
            drag: function (event, ui) {
                // oben
                var neubauMapTop = $("#NeubauMap").position().top;
                var uiTop = $(this).position().top;
                var delta_uiTop_neubauMapTop = uiTop - neubauMapTop;

                // links
                var neubauMapLeft = $("#NeubauMap").position().left;
                var uiLeft = $(this).position().left;
                var delta_uiLeft_neubauMapLeft = uiLeft - neubauMapLeft;

                // imgIcon verlässt oben oder links die NeubauMap -> Border für die Raumliste erscheint, um anzuzeigen, dass hier gedroppt werden darf
                if (delta_uiTop_neubauMapTop < -10 || delta_uiLeft_neubauMapLeft < -20) { // Werte etwas unter Null, da Icons erst ein Stück aus der Map heraus gezogen werden sollen
                    $('#AltbauListe').css('border', '2px solid green');
                } else {
                    $('#AltbauListe').css('border', '');
                }
            },
            stack: '.moveitplaner' // Icons (genauer: alle Elemente mit class='moveitplaner') beim Draggen immer im Vordergrund
        }).droppable({
            drop: function (event, ui) {
                ui.draggable.draggable('option', 'revert', true);
            }
        });
    });*/
};

/* Items laden/////////////////////////////////////////////////// */

//Items aus dem Altbau laden
function getItems(roomId) {
    if (typeof roomId != 'undefined' && roomId != '' && mainSettings.isLoggedIn) {
        // Lade Items des Raums in Auswahlliste
        $.ajax({
            type: 'POST',
            url: BASEURL + 'api/getItems/room/' + roomId,
            dataType: 'json',
            success: function (data) {
                var itemsHTML = '';

                if (data.items.length > 0) {
                    if (data.owner) {
                        $.each(data.items, function (key, value) {
                            itemsHTML += '<div class="ui-state-default" data-title="' + value.item_description + '" data-item-id="' + value.item_id + '" data-img="' + itemTypes[value.item_type_id].item_type_picture + '">' + value.item_description + '</div>';
                        });
                        $('#AltbauListe').html(itemsHTML);
                        $('#AltbauListe').css({
                            'height': 200,
                            'overflow': 'auto'
                        });
                    } else {
                        $('#AltbauListe').html('<div class="alert alert-info">Sie sind nicht der Eigentümer.</div>');
                    }
                } else {
                    $('#AltbauListe').html('<div class="alert alert-info">In diesem Raum sind keine Möbel.</div>');
                }
            }
        });
    } else {
        $('#AltbauListe').html('<div class="alert alert-info">Bitte einen Raum oben auswählen, um dessen Möbel zu sehen!</div>');
    }
}

//Items aus dem Lager laden
function getItemsVirtualRooms(roomList, api){
    if(mainSettings.isLoggedIn){
        $.ajax({
            type: 'POST',
            url: BASEURL + api,
            dataType: 'json',
            success: function(data){
                var itemsHTML = '';

                if(data.items.length > 0){
                    $.each(data.items, function(key, value){
                        itemsHTML += '<div class="ui-state-default" data-title="' + value.item_description + '" data-item-id="' + value.item_id + '" data-img="' + itemTypes[value.item_type_id].item_type_picture + '">' + value.item_description + '</div>';
                    });
                    $(roomList).html(itemsHTML);
                } else{
                    $(roomList).html('<div class="alert alert-info">Hier befinden sich derzeit keine Möbel.</div>');
                }
            }
        });
    }
}

// Lade Raum
function getRoom(roomId) {
    if (typeof roomId != 'undefined' && roomId != '' && mainSettings.isLoggedIn) {
        $.ajax({
            type: 'POST',
            url: BASEURL + 'api/getRoom/' + roomId,
            dataType: 'json',
            success: function (room) {


            }
        });
    } else {

    }
}

//ZOLLSTOCK ANFANG

// Bei Klick auf den ZollstockButton erscheinen 2 'Messpunkte' in der NeubauMap
// und die Beschriftung des ZollstockButton ändert sich
var distance = 0;
$('#Zollstock').click(function (e) {

	var offset = $(this).offset();
    var posX = (e.pageX  - offset.left);
    var posY = (e.pageY  - offset.top);
	
	if( $('#Zollstock').text() == "Zollstock anzeigen") {
	
		// Abstandsanzeige einblenden - label und inputfield
		$('.abstand-anzeige').css("visibility", "visible");
		
		// ButtonText ändern
		$('#Zollstock').text("Zollstock verstecken");
		
		// Messpunkte hinzufügen
		// erster Messpunkt
		$('#NeubauMap').append('<div class="dot" style="left: ' + (posX+100) + 'px; top: ' + (posY+100) + 'px"></div>');
		// zweiter Messpunkt
		$('#NeubauMap').append('<div class="dot" style="left: ' + (posX+50) + 'px; top: ' + (posY+100) + 'px"></div>');
		
		// Distanz messen
		calcDistance(); // fügt auch den gemessenen Wert in das inputfield ein
	}
	else {
		// Abstandsanzeige ausblenden
		$('.abstand-anzeige').css("visibility", "hidden");
		
		// ButtonText ändern
		$('#Zollstock').text("Zollstock anzeigen");
		
		// Messpunkte entfernen
		$('#NeubauMap .dot').remove();
	}	
		
    $("#NeubauMap .dot").draggable({
        containment: "parent",
        drag: function () {
            calcDistance();
        }
    });
});

//Distanz berechnen
function calcDistance() {
    var startingTop = parseFloat($('.dot').css('top'), 10),
            startingLeft = parseFloat($('.dot').css('left'), 10),
            startingTop2 = parseFloat($('.dot').last().css('top'), 10),
            startingLeft2 = parseFloat($('.dot').last().css('left'), 10),
            math = Math.round(Math.sqrt(Math.pow(startingTop - startingTop2, 2) + Math.pow(startingLeft - startingLeft2, 2)));

    $('#abstand-inline-inputfield').val(math);
}

//ZOLLSTOCK ENDE
