var api = {
    'AltbauTrakt': {
        'url': BASEURL + 'api/getFloors/building/'
    },
    'AltbauEtage': {
        'url': BASEURL + 'api/getRooms/map/',
        'initial_target_html': '<option value="">Vorher Trakt w&auml;hlen...</option>'
    },
    'AltbauRaum': {
        'initial_target_html': '<option value="">Vorher Etage w&auml;hlen...</option>'
    },
    'NeubauTrakt': {
        'url': BASEURL + 'api/getFloors/building/'
    },
    'NeubauEtage': {
        'url': BASEURL + 'api/getRooms/map/',
        'initial_target_html': '<option value="">Vorher Trakt w&auml;hlen...</option>'
    },
    'NeubauRaum': {
        'initial_target_html': '<option value="">Vorher Etage w&auml;hlen...</option>'
    }
};

$(document).ready(function ($) {

    // Altbau
    $("#AltbauTrakt").selectmenu({
        change: function (event, ui) {
            changed('AltbauTrakt', 'AltbauEtage');
            getItems(roomId);
        }
    });

    $("#AltbauEtage").selectmenu({
        change: function (event, ui) {
            changed('AltbauEtage', 'AltbauRaum');
            getItems(roomId);
        }
    });

    $("#AltbauRaum").selectmenu({
        change: function (event, ui) {
            roomId = $('#AltbauRaum').val();
            getItems(roomId);
        }
    });

    $('#AltbauTrakt').change(function (e) {
        changed('AltbauTrakt', 'AltbauEtage');
    });

    $('#AltbauEtage').change(function (e) {
        changed('AltbauEtage', 'AltbauRaum');
    });

    $('#AltbauRaum').change(function (e) {
        roomId = $('#AltbauRaum').val();
        getItems(roomId);
    });


    // Neubau

    $("#NeubauTrakt").selectmenu({
        change: function (event, ui) {
            changed('NeubauTrakt', 'NeubauEtage');
            getRoom(roomId);
        }
    });

    $("#NeubauEtage").selectmenu({
        change: function (event, ui) {
            changed('NeubauEtage', 'NeubauRaum');
            getRoom(roomId);
        }
    });

    $("#NeubauRaum").selectmenu({
        change: function (event, ui) {
            roomId = $('#NeubauRaum').val();
            getRoom(roomId);
        }
    });

    $('#NeubauTrakt').change(function (e) {
        changed('NeubauTrakt', 'NeubauEtage');
    });

    $('#NeubauEtage').change(function (e) {
        changed('NeubauEtage', 'NeubauRaum');
    });

    $('#NeubauRaum').change(function (e) {
        roomId = $('#NeubauRaum').val();
        getItems(roomId);
    });


    function changed(list_select_id, list_target_id) {
        //Grab the chosen value on first select list change
        var selectvalue = $('#' + list_select_id).val();

        //Display 'loading' status in the target select list
        $('#' + list_target_id).html('<option value="">Laden...</option>');


        // Altbau
        if (list_select_id === "AltbauTrakt") {
            if (selectvalue == "") {
                //Aufforderung anzeigen, vorige Felder auszuwählen
                $('#AltbauEtage').html(api.AltbauEtage.initial_target_html);
                $('#AltbauRaum').html(api.AltbauRaum.initial_target_html);
                $('#AltbauEtage').selectmenu("refresh");
                $('#AltbauRaum').selectmenu("refresh");

                roomId = $('#AltbauRaum').val();
                getItems(roomId);
            } else {
                $('#AltbauEtage').html(api.AltbauEtage.initial_target_html);
                $('#AltbauRaum').html(api.AltbauRaum.initial_target_html);

                roomId = $('#AltbauRaum').val();
                getItems(roomId);

                //Make AJAX request, using the selected value as the GET
                $.ajax({
                    url: api.AltbauTrakt.url + selectvalue,
                    success: function (output) {
                        var floors = output.floors;
                        $('#' + list_target_id).html('<option value="">Etage</option>');
                        for (var i = 0; i < floors.length; i++) {
                            if (floors[i].map_floor === 0) {
                                $('#' + list_target_id).append('<option value="' + floors[i].map_id + '"> Erdgeschoss </option>');
                            } else if (floors[i].map_floor < 0) {
                                $('#' + list_target_id).append('<option value="' + floors[i].map_id + '">' + Math.abs(floors[i].map_floor) + '. Untergeschoss </option>');
                            } else {
                                $('#' + list_target_id).append('<option value="' + floors[i].map_id + '">' + floors[i].map_floor + '. Etage </option>');
                            }

                            $('#AltbauEtage').selectmenu("refresh");
                            $('#AltbauRaum').selectmenu("refresh");
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + " " + thrownError);
                    }
                });
            }
        } else if (list_select_id === "AltbauEtage") {
            //Aufforderung anzeigen, vorige Felder auszuwählen
            if (selectvalue == "") {
                $('#AltbauRaum').html(api.AltbauRaum.initial_target_html);
                $('#AltbauRaum').selectmenu("refresh");

                roomId = $('#AltbauRaum').val();
                getItems(roomId);
            } else {
                $('#AltbauRaum').html(api.AltbauRaum.initial_target_html);

                roomId = $('#AltbauRaum').val();
                getItems(roomId);

                $.ajax({
                    url: BASEURL + 'api/getRooms/map/' + selectvalue,
                    success: function (output) {
                        var rooms = output.rooms;
                        $('#' + list_target_id).html('<option value="">R&auml;ume</option>');
                        var ownedItems = 0;
                        for (var i = 0; i < rooms.length; i++) {
                            if(rooms[i].owner){
                                $('#' + list_target_id).append('<option value="' + rooms[i].room_id + '">' + rooms[i].room_name + '</option>');
                                ownedItems++;
                            }
                        }

                        if(ownedItems == 0){
                            $('#' + list_target_id).html('<option value="">Keine R&auml;ume vorhanden</option>');
                        }

                        $('#AltbauRaum').selectmenu("refresh");
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + " " + thrownError);
                    }
                });
            }
        }

        // Neubau
        else if (list_select_id === "NeubauTrakt") {
            if (selectvalue == "") {
                //Aufforderung anzeigen, vorige Felder auszuwählen
                $('#NeubauEtage').html(api.NeubauEtage.initial_target_html);
                $('#NeubauRaum').html(api.NeubauRaum.initial_target_html);
                $('#NeubauEtage').selectmenu("refresh");
                $('#NeubauRaum').selectmenu("refresh");

                roomId = $('#NeubauRaum').val();
                getItems(roomId);
            } else {
                $('#NeubauEtage').html(api.NeubauEtage.initial_target_html);
                $('#NeubauRaum').html(api.NeubauRaum.initial_target_html);

                roomId = $('#NeubauRaum').val();
                getItems(roomId);

                //Make AJAX request, using the selected value as the GET
                $.ajax({
                    url: api.NeubauTrakt.url + selectvalue,
                    success: function (output) {
                        var floors = output.floors;
                        $('#' + list_target_id).html('<option value="">Etage</option>');
                        for (var i = 0; i < floors.length; i++) {
                            if (floors[i].map_floor === 0) {
                                $('#' + list_target_id).append('<option value="' + floors[i].map_id + '"> Erdgeschoss </option>');
                            } else if (floors[i].map_floor < 0) {
                                $('#' + list_target_id).append('<option value="' + floors[i].map_id + '">' + Math.abs(floors[i].map_floor) + '. Untergeschoss </option>');
                            } else {
                                $('#' + list_target_id).append('<option value="' + floors[i].map_id + '">' + floors[i].map_floor + '. Etage </option>');
                            }

                            $('#NeubauEtage').selectmenu("refresh");
                            $('#NeubauRaum').selectmenu("refresh");
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + " " + thrownError);
                    }
                });
            }
        } else if (list_select_id === "NeubauEtage") {
            //Aufforderung anzeigen, vorige Felder auszuwählen
            if (selectvalue == "") {
                $('#NeubauRaum').html(api.NeubauRaum.initial_target_html);
                $('#NeubauRaum').selectmenu("refresh");

                roomId = $('#NeubauRaum').val();
                getRoom(roomId);
            } else {
                $('#NeubauRaum').html(api.NeubauRaum.initial_target_html);

                roomId = $('#NeubauRaum').val();
                getRoom(roomId);

                $.ajax({
                    url: BASEURL + 'api/getRooms/map/' + selectvalue,
                    success: function (output) {
                        var rooms = output.rooms;
                        $('#' + list_target_id).html('<option value="">R&auml;ume</option>');
                        var ownedRooms = 0;
                        for (var i = 0; i < rooms.length; i++) {
                            if(rooms[i].owner){
                                $('#' + list_target_id).append('<option value="' + rooms[i].room_id + '">' + rooms[i].room_name + '</option>');
                                ownedRooms++;
                            }
                        }

                        if(ownedRooms == 0){
                            $('#' + list_target_id).html('<option value="">Keine R&auml;ume vorhanden</option>');
                        }

                        $('#NeubauRaum').selectmenu("refresh");
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + " " + thrownError);
                    }
                });
            }
        }
    }
});
