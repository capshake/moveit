function formatFloor(floor) {
    if (floor < 0) {
        return Math.abs(floor) + '. Untergeschoss';
    }
    else if (floor > 0) {
        return floor + '. Etage';
    }
    else {
        return 'Erdgeschoss';
    }
}

function loadAltbauList(roomId) {
    if (typeof roomId != 'undefined' && roomId !== '') {
        var html = '';

        // Items in die Auswahlliste laden
        $.ajax({
            type: 'POST',
            url: BASEURL + 'api/getItems/room/' + roomId,
            datatype: 'json',
            success: function (data) {

                if (data.items.length > 0) {
                    if (data.owner) {
                        $.each(data.items, function (key, value) {
                            html += '<div class="altbau-item ui-state-default" data-width="' + value.item_size_x + '" data-height="' + value.item_size_y + '" data-title="' + value.item_description + '" data-item-id="' + value.item_id + '" data-img="' + itemTypes[value.item_type_id].item_type_picture + '">' + value.item_description + '</div>';
                        });
                        $('#AltbauListe').html(html);

                        dragAndDrop();
                    }
                    else {
                        $('#AltbauListe').html('<div class="alert alert-info">Sie verfügen nicht über Bearbeitungsrechte für diesen Raum.</div>');
                    }
                }
                else {
                    $('#AltbauListe').html('<div class="alert alert-info">In diesem Raum sind keine Möbel.</div>');
                }
            }
        });
    }
    else {
        $('#AltbauListe').html('<div class="alert alert-info">Bitte einen Raum auswählen.</div>');
    }
}

function loadNeubauRoom(roomId) {
    if (typeof roomId != 'undefined' && roomId != '') {
        $.ajax({
            type: 'POST',
            url: BASEURL + 'api/getRoom/' + roomId,
            datatype: 'json',
            success: function (data) {
                if (data.rooms[0].room_size_x != null && data.rooms[0].room_size_y != null) {
                    $('#NeubauMap').html('<div class="main-room"></div>');

                    $('.main-room').css({
                        'width': data.rooms[0].room_size_x,
                        'height': data.rooms[0].room_size_y,
                    });
                    $('.main-room').data('room-id', roomId);

                    $.ajax({
                        type: 'POST',
                        url: BASEURL + 'api/getItems/room/' + roomId,
                        datatype: 'json',
                        success: function (data) {
                            if (data.owner) {
                                $.each(data.items, function (key, value) {
                                    $(".main-room").append('<img data-toggle="tooltip" title="' + value.item_description + '" data-width="' + value.item_size_x + '" data-height="' + value.item_size_y + '" data-title="' + value.item_description + '" data-img="' + itemTypes[value.item_type_id].item_type_picture + '" data-item-id="' + value.item_id + '" class="planner-item-' + value.item_id + ' room-item" src="' + itemTypes[value.item_type_id].item_type_picture + '">');


                                    $('.planner-item-' + value.item_id).css({
                                        'position': 'absolute',
                                        top: value.item_position_y + 'px',
                                        left: value.item_position_x + 'px',
                                        'z-index': 4,
                                        'width': value.item_size_x,
                                        'height': value.item_size_y,
                                        'transform': 'rotate(' + value.item_orientation + 'deg)',
                                        'background-color': '#E8E8E8'
                                    }).attr("rotation-value", value.item_orientation).on("dblclick", {
                                        itemid: value.item_id
                                    }, rotate);


                                    /*$('.planner-item-' + value.item_id).css({
                                     'position': 'absolute',
                                     'top': event.pageY - $('.main-room').offset().top,
                                     'left': event.pageX - $('.main-room').offset().left,
                                     'z-index': 4
                                     }).on("dblclick", {
                                     itemid: value.item_id
                                     }, rotate);*/
                                });
                                dragAndDrop();
                            }
                            else {
                                $('#NeubauMap').html('<div class="alert alert-info">Sie verfügen nicht über Bearbeitungsrechte für diesen Raum.</div>');
                            }
                        }
                    });
                }
                else {
                    $('#NeubauMap').html('<div class="alert alert-info">Diesem Raum wurde noch keine Größe gegeben. Bitte benachrichtigen Sie Ihren Administrator!</div>');
                }
            }
        });
    }
    else {
        $('#NeubauMap').html('<div class="alert alert-info">Bitte einen Raum auswählen.</div>');
    }
}

function loadVirtualList() {
    var virtualRooms = [{'room': '#LagerListe', 'url': 'api/getItems/store/user', 'itemClass': 'lager-item'},
        {'room': '#MuellListe', 'url': 'api/getItems/trash', 'itemClass': 'muell-item'},
        {'room': '#oeffentlichesLagerListe', 'url': 'api/getItems/store/all', 'itemClass': 'oefflager-item'}];

    $.each(virtualRooms, function (key, val) {
        $.ajax({
            type: 'POST',
            url: BASEURL + val['url'],
            datatype: 'json',
            success: function (data) {
                var html = '';

                if (data.items.length > 0) {

                    $.each(data.items, function (key, value) {
                        html += '<div class="' + val['itemClass'] + ' ui-state-default" data-width="' + value.item_size_x + '" data-height="' + value.item_size_y + '" data-title="' + value.item_description + '" data-item-id="' + value.item_id + '" data-img="' + itemTypes[value.item_type_id].item_type_picture + '">' + value.item_description + '</div>';
                    });

                    $(val['room']).html(html);
                }
                else {
                    $(val['room']).html('<div class="alert alert-info">Hier befinden sich derzeit keine Möbel.</div>');
                }
                dragAndDrop();
            }
        });
    });

    $.ajax({
        type: 'POST',
    });
}

$(document).ready(function ($) {
    if (mainSettings.isLoggedIn) {
        // Virtuelle Räume laden
        loadVirtualList();

        // Hinweis auf Raumauswahl anzeigen
        loadAltbauList(undefined);
        loadNeubauRoom(undefined);

        // Neulade-Button bei öffentlichem Lager
        $("#btnOeffReset").click(function () {
            loadVirtualList();
        });

        // Altbau
        $('#AltbauTrakt').change(function (e) { // AltbauTrakt geändert, AltbauEtage aktualisieren
            // Wert des AltbauTrakt-Dropdown holen
            var selectvalue = $('#AltbauTrakt').val();

            // Hinweis auf Raumauswahl anzeigen
            loadAltbauList(undefined);

            if (selectvalue === '') {
                $('#AltbauEtage').html('<option value="">Vorher Trakt wählen</option>');
                $('#AltbauRaum').html('<option value="">Vorher Etage wählen</option>');
            }
            else {
                $.ajax({
                    url: BASEURL + 'api/getFloors/building/' + selectvalue,
                    success: function (data) {
                        var floors = data.floors;
                        var html = '';

                        // Erstes Feld mit Feldnamen vorbelegen
                        $('#AltbauEtage').html('<option value="">Etage</option>');

                        // Ausgewähltes Gebäude hat keine Etagen
                        if (floors.length === 0) {
                            $('#AltbauEtage').html('<option value="">Keine Etage vorhanden</option>');
                            $('#AltbauRaum').html('<option value="">Vorher Etage wählen</option>');
                        }
                        else {
                            $.each(floors, function (key, value) {
                                html += '<option value="' + value.map_id + '">' + formatFloor(value.map_floor) + '</option>';
                            });
                            $('#AltbauEtage').append(html);
                        }
                        dragAndDrop();
                    }
                });
            }
        });

        $('#AltbauEtage').change(function (e) { // AltbauEtage geändert, AltbauRaum aktualisieren
            // Wert des AltbauEtage-Dropdown holen
            var selectvalue = $('#AltbauEtage').val();

            // Hinweis auf Raumauswahl anzeigen
            loadAltbauList(undefined);

            if (selectvalue === '') {
                $('#AltbauRaum').html('<option value="">Vorher Etage wählen</option>');
            }
            else {
                $.ajax({
                    url: BASEURL + 'api/getRooms/map/' + selectvalue,
                    success: function (data) {
                        var rooms = data.rooms;
                        var html = '';

                        // Erstes Feld mit Feldnamen vorbelegen
                        $('#AltbauRaum').html('<option value="">Räume</option>');

                        // Ausgewählte Etage hat keine Räume
                        if (rooms.length === 0) {
                            $('#AltbauRaum').html('<option value="">Kein Raum vorhanden</option>');
                        }
                        else {
                            $.each(rooms, function (key, value) {
                                if (value.owner) {
                                    html += '<option value="' + value.room_id + '">' + value.room_name + '</option>';
                                }
                            });
                            if (html !== '') {
                                $('#AltbauRaum').append(html);
                            }
                            else {
                                $('#AltbauRaum').html('<option value="">Kein Raum mit Bearbeitungsrechten</option>');
                            }
                        }
                        dragAndDrop();
                    }
                });
            }
        });

        $('#AltbauRaum').change(function (e) { // AltbauRaum geändert, Itemliste laden
            roomId = $('#AltbauRaum').val();
            loadAltbauList(roomId);
        });


        // Neubau
        $('#NeubauTrakt').change(function (e) { // NeubauTrakt geändert, NeubauEtage aktualisieren
            var selectvalue = $('#NeubauTrakt').val();
            var html = '';

            // Hinweis auf Raumauswahl anzeigen
            loadNeubauRoom(undefined);

            if (selectvalue === '') {
                $('#NeubauEtage').html('<option value="">Vorher Trakt wählen</option>');
                $('#NeubauRaum').html('<option value="">Vorher Etage wählen</option>');
            }
            else {
                $.ajax({
                    url: BASEURL + 'api/getFloors/building/' + selectvalue,
                    success: function (data) {
                        var floors = data.floors;

                        // Erstes Feld mit Feldnamen vorbelegen
                        $('#NeubauEtage').html('<option value="">Etage</option>');

                        // Ausgewähltes Gebäude hat keine Etagen
                        if (floors.length === 0) {
                            $('#NeubauEtage').html('<option value"">Keine Etage vorhanden</option>');
                            $('#NeubauRaum').html('<option value="">Vorher Etage wählen</option>');
                        }
                        else {
                            $.each(floors, function (key, value) {
                                html += '<option value="' + value.map_id + '">' + formatFloor(value.map_floor) + '</option>';
                            });
                            $('#NeubauEtage').append(html);
                        }
                        dragAndDrop();
                    }
                });
            }
        });

        $('#NeubauEtage').change(function (e) { // NeubauEtage geändert, NeubauRaum aktualisieren
            var selectvalue = $('#NeubauEtage').val();
            var html = '';

            // Hinweis auf Raumauswahl anzeigen
            loadNeubauRoom(undefined);

            if (selectvalue === '') {
                $('#NeubauRaum').html('<option value="">Vorher Etage wählen</option>');
            }
            else {
                $.ajax({
                    url: BASEURL + 'api/getRooms/map/' + selectvalue,
                    success: function (data) {
                        var rooms = data.rooms;

                        // Erstes Feld mit Feldnamen vorbelegen
                        $('#NeubauRaum').html('<option value="">Räume</option>');

                        // Ausgewählte Etage hat keine Räume
                        if (rooms.length === 0) {
                            $('#NeubauRaum').html('<option value="">Kein Raum vorhanden</option>');
                        }
                        else {
                            $.each(rooms, function (key, value) {
                                if (value.owner) {
                                    html += '<option value="' + value.room_id + '">' + value.room_name + '</option>';
                                }
                            });
                            if (html !== '') {
                                $('#NeubauRaum').append(html);
                            }
                            else {
                                $('#NeubauRaum').html('<option value="">Kein Raum mit Bearbeitungsrechten</option>');
                            }

                        }
                        dragAndDrop();
                    }
                });
            }
        });

        $('#NeubauRaum').on('change', function (e) { // NeubauRaum geändert, Raumkarte laden
            roomId = $('#NeubauRaum').val();
            loadNeubauRoom(roomId);

            dragAndDrop();
        });



        $('#NeubauTraktMap').on('change', function (e) { // NeubauRaum geändert, Raumkarte laden
            var mapId = $('#NeubauTraktMap').val();

            if (typeof mapId != 'undefined' && mapId != 0) {
                $.ajax({
                    type: 'POST',
                    url: BASEURL + 'api/getMap/' + mapId,
                    dataType: 'json',
                    success: function (data) {
                        if (data.maps.length > 0) {

                            $('.frontend-groundplan-outer').remove();
                            $('#Map').html('<div class="frontend-groundplan-outer"><div class="frontend-groundplan"><div class="frontend-groundplan-inner"><img src="' + data.maps[0].map_picture + '"></div></div></div>');

                            $.ajax({
                                type: 'POST',
                                url: BASEURL + 'api/getRooms/map/' + mapId,
                                dataType: 'json',
                                success: function (data) {
                                    if (data.rooms.length > 0) {
                                        var scaleOneCm = data.map.room_scale_px / data.map.room_scale_cm;

                                        $.each(data.rooms, function (key, value) {
                                            if (value.room_position_x != null || value.room_position_y != null) {
                                                $('.frontend-groundplan-inner').append('<div class="map-room" data-roomid="' + value.room_id + '"><div class="name">' + value.room_name + '</div></div>');

                                                $('.frontend-groundplan-inner').find('.map-room[data-roomid="' + value.room_id + '"]').css({
                                                    'left': value.room_position_x + 'px',
                                                    'top': value.room_position_y + 'px',
                                                    'width': Math.round(value.room_size_x * scaleOneCm),
                                                    'height': Math.round(value.room_size_y * scaleOneCm)
                                                });
                                                if (value.room_owner) {
                                                    $('.frontend-groundplan-inner').find('.map-room[data-roomid="' + value.room_id + '"]').addClass('owner');
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    }
                });


            } else {
                $('.frontend-groundplan-outer').remove();
                $('#Map').html('<div class="alert alert-info">Wählen Sie eine Map aus.</div>');
            }

        });

        $('body').on('click', '.frontend-groundplan-outer .map-room', function () {
            var roomId = $(this).attr('data-roomid');
            loadNeubauRoom(roomId);

            dragAndDrop();
        });







    }
});
