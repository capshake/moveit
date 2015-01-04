$(document).ready(function () {
    $('form').prepend('<input type="hidden" name="token" value="' + mainSettings.csrfToken + '"/>');
});