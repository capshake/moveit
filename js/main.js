var roomId; // globale Variable für den ausgwählten Raum

$(document).ready(function () {

    roomId = 5;






    if (typeof roomId != 'undefined' && roomId != '') {

        // Lade Raum
        $.ajax({
            type: 'POST',
            url: BASEURL + 'api/getRoom/' + roomId,
            dataType: 'json',
            success: function (room) {



                // Lade Items des Raums in Auswahlliste
                $.ajax({
                    type: 'POST',
                    url: BASEURL + 'api/getItems/room/' + roomId,
                    dataType: 'json',
                    success: function (data) {
                        var itemsHTML = '';

                        if (data.items.length > 0) {
                            $.each(data.items, function (key, value) {
                                itemsHTML += '<li class="ui-state-default" data-type="' + value.item_description + '">' + value.item_description + '</li>';
                            });
                            $('#AltbauListe').html(itemsHTML);
                            $('#AltbauListe').css({
                                'height': 200,
                                'overflow': 'auto'
                            });
                        } else {
                            $('#AltbauListe').html('<div class="alert alert-info">Es gibt momentan keine Räume welche verteilt werden können.</div>');
                        }
                    }
                });

            }
        });

    }


});