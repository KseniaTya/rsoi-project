$( document ).ready(function() {
    loadHeader();

});

function loadHeader(){
    $.get("http://localhost:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function (jwt) {

        $.ajax({
            url: 'http://localhost:8080/api/v1/callback?jwt='+jwt,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var profile = data.profile;
                $.ajax({
                    url: 'http://localhost:8080/api/v1/rating',
                    type: 'GET',
                    dataType: 'json',
                    headers: {'token': jwt},
                    success: function (data) {

                        $('.userinfo').append('<span>'+profile+', рейтинг '+data.stars+'</span>')

                    }
                });

            }
        });


    });
}