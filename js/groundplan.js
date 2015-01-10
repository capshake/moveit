$(document).ready(function () {



    //Räume in Map laden
    if (typeof $('.groundplan').data('mapid') != 'undefined') {
        $.ajax({
            type: 'POST',
            url: BASEURL + 'api/getRooms/map/' + $('.groundplan').data('mapid'),
            dataType: 'json',
            success: function (data) {
                if (data.rooms.length > 0) {
                    $.each(data.rooms, function (key, value) {
                        if (value.room_position_x != null || value.room_position_y != null) {
                            $('.groundplan-inner').append('<div class="map-room" data-roomid="' + value.room_id + '"><div class="name">' + value.room_name + '</div></div>');

                            $('.groundplan-inner').find('.map-room[data-roomid="' + value.room_id + '"]').css({
                                'left': value.room_position_x + 'px',
                                'top': value.room_position_y + 'px',
                                'width': value.room_size_x,
                                'height': value.room_size_y
                            });
                            if (value.room_owner) {
                                $('.groundplan-inner').find('.map-room[data-roomid="' + value.room_id + '"]').addClass('owner');
                            }
                        }
                    });


                    $(".map-room").resizable({
                        containment: ".groundplan",
                        minHeight: 20,
                        minWidth: 20
                    });

                    $(".map-room").draggable({
                        containment: "parent"
                    });

                }
            }
        });
    }

    //Maßstab definieren
    var distance = 0;
    $('.groundplan.scale .groundplan-inner').click(function (e) {
        var offset = $(this).offset();
        var posX = (e.pageX - offset.left);
        var posY = (e.pageY - offset.top);

        if ($(this).find('.dot').length == 2) {
            $(this).find('.dot').first().remove();
        }
        $(this).append('<div class="dot" style="left: ' + posX + 'px; top: ' + posY + 'px"></div>');


        if ($(this).find('.dot').length == 2) {
            calcDistance();
        }

        $(".groundplan.scale .groundplan-inner .dot").draggable({
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

        $('.scale-value').val(math);
    }








    /*$(document).on('click', '.map-room', function () {
     
     $.ajax({
     type: 'POST',
     url: 'server/room_' + $(this).data('roomid') + '.json',
     dataType: 'json',
     success: function (data) {
     $('.groundplan-outer').hide();
     
     //Raum erstellt größe etc.
     $('.map-room-data .map-room-inner').css({
     'width': data.map - roomWidth,
     'height': data.map - roomHeight,
     });
     
     
     if (data.map - roomItems.length > 0) {
     $.each(data.map - roomItems, function (key, value) {
     
     if (value.item_description == 'Besprechungstisch') {
     $('.map-room-data .map-room-inner').append('<div class="item-table" data-itemid="' + value.item_id + '"></div>');
     
     $('.map-room-data .map-room-inner').find('.item-table[data-itemid="' + value.item_id + '"]').css({
     'left': value.item_position_x + 'px',
     'top': value.item_position_y + 'px',
     'width': value.item_size_x,
     'height': value.item_size_y
     });
     }
     });
     }
     
     $('.map-room-data').show();
     
     },
     error: function () {
     alert('fsdf');
     }
     });
     });*/

});