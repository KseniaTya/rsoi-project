$('.btn-libraries').click(function (e) {
    e.preventDefault();
    $('.libraries').text(' ');

    $.get("http://localhost:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function (jwt) {

        var token = jwt;

        console.log(token);


        $.ajax({
            url: 'http://localhost:8080/api/v1/libraries',
            type: 'GET',
            crossDomain: true,
            dataType: 'json',
            headers: {'token': token},
            success: function (data) {
                console.log("Кефтемек");
                console.log(data);
                let i = 0;
                data.items.forEach(function (el) {
                    $('.libraries').append('<dl><dt>' + el.name + '</dt> <dd>  <btn class="btn btn-success btn-info-libraries"> Перейти </btn> </dd></dl>');
                });

            }
        });


    });


});

$(document).on("click", ".libraries  .btn-info-libraries", function (e) {
    e.preventDefault();

    $.get("http://localhost:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function (jwt) {

        var token = jwt;
        $.ajax({
            url: 'http://localhost:8080/api/v1/libraries/83575e12-7ce0-48ee-9931-51919ff3c9ee/books?page=1&size=25&showAll=true',
            type: 'GET',
            crossDomain: true,
            dataType: 'json',
            headers: {'token': token},
            success: function (data) {

                console.log(data);
                data.items.forEach(function (el) {
                    if (el.availableCount == 0) {
                        $('.libraries').append('<dl><dt>' + el.name + '</dt> <dt>' + el.author + '</dt> <dd>Доступно: ' + el.availableCount + '</dd> </dl>');
                    } else {
                        $('.libraries').append('<dl><dt>' + el.name + '</dt> <dt>' + el.author + '</dt> <dd>Доступно: ' + el.availableCount + '</dd> <dd>  <btn class="btn btn-success "> Забронировать </btn> </dd></dl>');
                    }
                });

            }
        });


    });
});
  