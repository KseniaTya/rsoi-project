$('.btn-libraries').click(function (e) {
    e.preventDefault();
    $('.libraries').text(' ');

    $.get("http://localhost:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function (jwt) {

        var token = jwt;

        console.log(token);


        $.ajax({
            url: 'http://localhost:8080/api/v1/libraries',
            type: 'GET',
            dataType: 'json',
            headers: {'token': token},
            success: function (data) {
                $('.libraries').append('<hr class="hr-type-1">');
                data.items.forEach(function (el) {
                    $('.libraries').append('<dl><dt>' + el.name + '</dt> <dd> ' +
                        ' <btn class="btn btn-success btn-info-libraries" value="' + el.libraryUid + '"> Перейти </btn> ' +
                        '</dd></dl><hr class="hr-type-2">');
                });


            }
        });


    });


});

$(document).on("click", ".libraries  .btn-info-libraries", function (e) {
    e.preventDefault();
    $('.libraries').text(' ');
    let libraryUid = $(this).attr('value');
    $.get("http://localhost:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function (jwt) {

        var token = jwt;
        $.ajax({
            url: 'http://localhost:8080/api/v1/libraries/' + libraryUid + '/books?page=1&size=25&showAll=true',
            type: 'GET',
            dataType: 'json',
            headers: {'token': token},
            success: function (data) {
                $('.libraries').append('<hr class="hr-type-1">');

                let i = 0;
                data.items.forEach(function (el) {
                    if (el.availableCount == 0) {
                        $('.libraries').append('<dl><dt>' + el.name + '</dt> <dt>' + el.author + '</dt> <dd>Доступно: ' + el.availableCount + '</dd> </dl>');
                    } else {
                        $('.libraries').append('<dl><dt>' + el.name + '</dt> <dt>' + el.author + '</dt> <dd>Доступно: ' + el.availableCount + '</dd> <dd>  ' +
                            '<btn class="btn btn-success reservate" value="' + i + '"> Забронировать </btn> </dd></dl>' +
                            '<input type="hidden" id="bookUid' + i + '" name="bookUid" value= "' + el.bookUid + '"/> ' +
                            '<input type="hidden" id="libraryUid' + i + '" name="libraryUid" value= "' + libraryUid + '"/>'
                        );
                    }
                    $('.libraries').append('<hr class="hr-type-2">');
                    i++;
                });

            }
        });


    });
});

$(document).on("click", ".reservate", function (e) {
    let i = $(this).attr('value');
    let bookUid = $(`input[id="bookUid${i}"]`).val();
    let libraryUid = $(`input[id="libraryUid${i}"]`).val();

    const currentDate = new Date();
    currentDate.setDate(currentDate.getDate() + 10);
    const formattedDate = currentDate.toISOString().slice(0, 10);



    $.get("http://localhost:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function (jwt) {

        $.ajax({
            url: 'http://localhost:8080/api/v1/reservations/',
            type: 'POST',
            contentType: "application/json",
            headers: {'token': jwt},
            data: JSON.stringify({
                "bookUid": bookUid,
                "libraryUid": libraryUid,
                "tillDate": formattedDate
            }),
            success: function (data) {
                $('.btn-token').trigger('click');
                $('.btn-libraries').trigger('click');
            }
        });


    });
});
