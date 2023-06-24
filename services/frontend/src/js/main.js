$('.btn-token').click(function (e) {
    e.preventDefault();
    $('.books-reservations').text(' ');

    var token;
    $.get("http://localhost:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function (jwt) {
        token = jwt;
        $.ajax({
            url: 'http://localhost:8080/api/v1/reservations',
            type: 'GET',
            dataType: 'json',
            headers: {'token': token},
            success: function (data) {

                let i = 0;
                $('.books-reservations').append('<hr class="hr-type-1">');
                data.forEach(function (el) {
                    $('.books-reservations').append('<dl><dt>' + el.book.name + '</dt> <dd>' + el.book.author + '</dd> <dd>  ' +
                        '<input type="hidden" id="reservationUid' + i + '" name="reservationUid" value= "' + el.reservationUid + '"/> ' +
                        '<input type="hidden" id="tillDate' + i + '" name="tillDate" value= "' + el.tillDate + '"/> ' +
                        '<input type="hidden" id="bookUid' + i + '" name="bookUid" value= "' + el.book.bookUid + '"/> ' +
                        '<input type="hidden" id="libraryUid' + i + '" name="libraryUid" value= "' + el.library.libraryUid + '"/>');
                    if (el.status == "RENTED") {
                        $('.books-reservations').append('<btn class="btn btn-success btn-return-book" value="' + i + '"> Вернуть </btn> </dd></dl>');
                    } else {
                        $('.books-reservations').append('(Возвращен)');
                    }
                    $('.books-reservations').append('<hr class="hr-type-2">');
                    i++;
                });

            }
        });

    });
});

$(document).on("click", ".btn-return-book", function (e) {
    e.preventDefault();
    let i = $(this).attr('value');
    let reservationUid = $(`input[id="reservationUid${i}"]`).val();
    const today = new Date();
    const formattedDate = today.toISOString().slice(0, 10);


    $.get("http://localhost:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function (jwt) {

        $.ajax({
            url: 'http://localhost:8080/api/v1/reservations/' + reservationUid + '/return',
            type: 'POST',
            contentType: "application/json",
            headers: {'token': jwt},
            data: JSON.stringify({
                condition: 'EXCELLENT',
                date: formattedDate
            }),
            success: function (data) {
                loadHeader();
                $('.btn-token').trigger('click');
                $('.btn-libraries').trigger('click');
            }
        });


    });
});


    
