var BASEURL = '/moveit/';

$(document).ready(function () {
    //An jedes Formular einen Token heften
    $('form').prepend('<input type="hidden" name="token" value="' + mainSettings.csrfToken + '"/>');
    $('.logout').attr('href', BASEURL + 'logout/' + mainSettings.csrfToken);





    //Popup vor dem Löschvorgang
    $('body').on('click', '.delete-button', function () { 
        var el = $(this);
        bootbox.confirm("Sind Sie sich sicher?", function (result) {
            if(result) {
                location.href = el.attr('href');
            }
        });
        return false;
    });




});