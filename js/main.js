var roomId; // globale Variable für den ausgwählten Raum

function getItems(roomId){
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
                            $('#AltbauListe').html('<div class="alert alert-info">Es gibt in diesem Raum keine Items welche verteilt werden können.</div>');
                        }
                    }
                });

            }
        });
    }
}



$(document).ready(function () {
    roomId = 110;
});
