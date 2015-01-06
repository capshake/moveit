var BASEURL = '/moveit/';

$(document).ready(function () {
    //An jedes Formular einen Token heften
    $('form').prepend('<input type="hidden" name="token" value="' + mainSettings.csrfToken + '"/>');
    $('.logout').attr('href', BASEURL+'logout/'+mainSettings.csrfToken);
});