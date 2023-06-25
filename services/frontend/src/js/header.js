$(document).ready(function () {
    loadHeader();

});

function loadHeader() {

    $.ajax({
        url: 'http://localhost:8080/api/v1/callback?jwt=' + JWT,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var profile = data.profile;
            $.ajax({
                url: 'http://localhost:8080/api/v1/rating',
                type: 'GET',
                dataType: 'json',
                headers: {'token': JWT},
                success: function (data) {
                    $('.userinfo').text(profile + ', рейтинг ' + data.stars)

                }
            });

        }
    });


}