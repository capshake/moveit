$(document).ready(function () {



    //Räume in Map laden
    if (typeof $('.groundplan').data('mapid') != 'undefined') {
        $.ajax({
            type: 'POST',
            url: BASEURL + 'api/getRooms/map/' + $('.groundplan').data('mapid'),
            dataType: 'json',
            success: function (data) {
                if (data.rooms.length > 0) {
                    var scaleOneCm = data.map.room_scale_px/data.map.room_scale_cm;
    console.log(scaleOneCm);
                    $.each(data.rooms, function (key, value) {
                        if (value.room_position_x != null || value.room_position_y != null) {
                            $('.groundplan-inner').append('<div class="map-room" data-roomid="' + value.room_id + '"><div class="name">' + value.room_name + '</div></div>');

                            $('.groundplan-inner').find('.map-room[data-roomid="' + value.room_id + '"]').css({
                                'left': value.room_position_x + 'px',
                                'top': value.room_position_y + 'px',
                                'width': Math.round(value.room_size_x*scaleOneCm),
                                'height': Math.round(value.room_size_y*scaleOneCm)
                            });
                            if (value.room_owner) {
                                $('.groundplan-inner').find('.map-room[data-roomid="' + value.room_id + '"]').addClass('owner');
                            }
                        }
                    });


                    $(".map-room").resizable({
                        containment: ".groundplan",
                        minHeight: 20,
                        minWidth: 20, 
                        stop: function() {
                            saveGroundplan();
                        }
                    });

                    $(".map-room").draggable({
                        containment: "parent", 
                        stop: function() {
                            saveGroundplan();
                        }
                    });
                }
            }
        });
    }

    $('body').on('click', '.add-room-groundplan-button', function () {




        var selectHTML = '';

        $.ajax({
            type: 'POST',
            url: BASEURL + 'api/getRooms/roomNotInMap/' + $('.groundplan').data('mapid'),
            dataType: 'json',
            success: function (data) {
                if (data.rooms.length > 0) {
                    $.each(data.rooms, function (key, value) {
                        selectHTML += '<option value="' + value.room_id + '">' + value.room_name + '</option>';
                    });
                    $('.map-add-room-outer').html('<label class="control-label" for="name">Name</label><select class="form-control map-add-room">' + selectHTML + '</select>');
                } else {
                    $('.map-add-room-outer').html('<div class="alert alert-info">Es gibt momentan keine Räume welche verteilt werden können.</div>');
                }
            }
        });






        bootbox.dialog({
            title: "Raum hinzufügen",
            message: '<div class="row"><div class="col-md-offset-2 col-md-8"><div class="form-group map-add-room-outer"> ' + selectHTML + '</div></div></div></div>',
            buttons: {
                success: {
                    label: "hinzufügen",
                    className: "btn-success",
                    callback: function () {
                        var roomId = $('.map-add-room').val();
                        var roomName = $('.map-add-room option[value=' + roomId + ']').text();

                        if (typeof roomId != 'undefined') {
                            $('.groundplan-inner').append('<div class="map-room" data-roomid="' + roomId + '"><div class="name">' + roomName + '</div></div>');
                            $('.groundplan-inner').find('.map-room[data-roomid="' + roomId + '"]').css({
                                'left': 50 + 'px',
                                'top': 50 + 'px',
                                'width': 100,
                                'height': 100
                            });

                            $(".map-room").resizable({
                                containment: ".groundplan",
                                minHeight: 20,
                                minWidth: 20, 
                                stop: function() {
                                    saveGroundplan();
                                }
                            });

                            $(".map-room").draggable({
                                containment: "parent", 
                                stop: function() {
                                    saveGroundplan();
                                }
                            });
                        }
                    }
                }
            }
        });
    });

    //Context Menu Raum
    var $contextMenu = $(".contextMenu");
    var contextRoomId;
    $("body").on("contextmenu", ".map-room", function (e) {
        contextRoomId = $(this).data('roomid');

        $contextMenu.css({
            display: 'block',
            left: e.pageX-370,
            top: e.pageY-150,
            position: 'absolute',
            'z-index': 9
        });
        return false;
    });
    $("body").click(function () {
        if(!$(this).hasClass('contextMenu')) {
            $contextMenu.hide();
        }
    });

    $contextMenu.on("click", "a", function () {
        $contextMenu.hide();

        $.ajax({
            type: "POST",
            url: BASEURL + 'admin/ajax/removeMapRoom',
            data: {
                token: mainSettings.csrfToken,
                room_id: contextRoomId,
            },
            dataType: 'json',
            success: function (data) {
                $('.save-groundplan .col-md-12').html('<div class="alert alert-success">' + data.msg + '</div>');
                $('.groundplan-inner').find('.map-room[data-roomid="' + contextRoomId + '"]').remove();
                saveGroundplan();
            }
        });
    });








    //Speichern aller Räume auf der Karte
    function saveGroundplan() {
        var roomsForSave = [];
        var roomsCount = 0;

        $.each($(".map-room"), function () {
            roomsForSave.push({
                room_position_y: parseInt($(this).css('top')),
                room_position_x: parseInt($(this).css('left')),
                room_size_x: parseInt($(this).css('width')),
                room_size_y: parseInt($(this).css('height')),
                room_id: parseInt($(this).attr('data-roomid'))
            });
            roomsCount++;
        });



        $.ajax({
            type: "POST",
            url: BASEURL + 'admin/ajax/saveMapRooms',
            data: {
                token: mainSettings.csrfToken,
                map_id: $('.groundplan').attr('data-mapid'),
                rooms: roomsForSave
            },
            dataType: 'json',
            success: function (data) {
                $('.save-groundplan .col-md-12').html('<div class="alert alert-success">' + data.msg + '</div>');
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


});