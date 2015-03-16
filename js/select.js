var api = {
    'AltbauTrakt' : {
        'url':'http://localhost/moveit/api/getFloors/building/'
    },
    'AltbauEtage':{
        'url':'http://localhost/moveit/api/getRooms/map/',
        'initial_target_html':'<option value="">Vorher Trakt w&auml;hlen...</option>'
    },
    'AltbauRaum':{
        'initial_target_html':'<option value="">Vorher Etage w&auml;hlen...</option>'
    }
};

$(document).ready(function($) {

    $("#AltbauTrakt").selectmenu({
        change: function(event, ui) {
            changed('AltbauTrakt', 'AltbauEtage');
        }
    });

    $("#AltbauEtage").selectmenu({
        change: function(event, ui) {
            changed('AltbauEtage', 'AltbauRaum');
        }
    });

    $("#AltbauRaum").selectmenu({
        change: function(event, ui) {
            getItems($('#AltbauRaum').val());
        }
    });

    $('#AltbauTrakt').change(function(e) {
        changed('AltbauTrakt', 'AltbauEtage');
    });

    $('#AltbauEtage').change(function(e){
        changed('AltbauEtage', 'AltbauRaum');
    });

    $('#AltbauRaum').change(function(e){
        getItems($('#AltbauRaum').val());
    });

    function changed(list_select_id, list_target_id){
        //Grab the chosen value on first select list change
        var selectvalue = $('#'+list_select_id).val();

        //Display 'loading' status in the target select list
        $('#'+list_target_id).html('<option value="">Laden...</option>');


        if(list_select_id === "AltbauTrakt"){
            if (selectvalue == "") {
                //Aufforderung anzeigen, vorige Felder auszuwählen
                $('#AltbauEtage').html(api.AltbauEtage.initial_target_html);
                $('#AltbauRaum').html(api.AltbauRaum.initial_target_html);
                $('#AltbauEtage').selectmenu("refresh");
                $('#AltbauRaum').selectmenu("refresh");
            }
            else{
                $('#AltbauEtage').html(api.AltbauEtage.initial_target_html);
                $('#AltbauRaum').html(api.AltbauRaum.initial_target_html);

                //Make AJAX request, using the selected value as the GET
                $.ajax({url: api.AltbauTrakt.url+selectvalue,
                    success: function(output) {
                        var floors = output.floors;
                        $('#'+list_target_id).html('<option value="">Etage</option>');
                        for(var i = 0; i < floors.length; i++){
                            if(floors[i].map_floor === 0){
                                $('#'+list_target_id).append('<option value="' + floors[i].map_id + '"> Erdgeschoss </option>');
                            }
                            else if(floors[i].map_floor < 0){
                                $('#'+list_target_id).append('<option value="' + floors[i].map_id + '">' + Math.abs(floors[i].map_floor) + '. Untergeschoss </option>');
                            }
                            else{
                                $('#'+list_target_id).append('<option value="' + floors[i].map_id + '">' + floors[i].map_floor + '. Etage </option>');
                            }

                            $('#AltbauEtage').selectmenu("refresh");
                            $('#AltbauRaum').selectmenu("refresh");
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + " "+ thrownError);
                }});
            }
        }
        else if(list_select_id === "AltbauEtage"){
            //Aufforderung anzeigen, vorige Felder auszuwählen
            if(selectvalue == ""){
                $('#AltbauRaum').html(api.AltbauRaum.initial_target_html);
                $('#AltbauRaum').selectmenu("refresh");
            }
            else{
                $('#AltbauRaum').html(api.AltbauRaum.initial_target_html);

                //Make AJAX request, using the selected value as the GET
                $.ajax({url: 'http://localhost/moveit/api/getRooms/map/'+selectvalue,
                    success: function(output) {
                        var rooms = output.rooms;
                        $('#'+list_target_id).html('<option value="">R&auml;ume</option>');
                        for(var i = 0; i < rooms.length; i++){
                            $('#'+list_target_id).append('<option value="' + rooms[i].room_id + '">' + rooms[i].room_name + '</option>');
                        }

                        $('#AltbauRaum').selectmenu("refresh");
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + " "+ thrownError);
                }});
            }
        }
    }
});
