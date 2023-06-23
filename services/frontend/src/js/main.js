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
            crossDomain: true,
            headers: {'token': token},
            success: function (data) {
                console.log("Кефтемек");
                console.log(data);
                let i = 0;
                data.forEach(function (el) {
                    if (el.status == "RENTED") {
                        $('.books-reservations').append('<dl><dt>' + el.book.name + '</dt> <dd>' + el.book.author + '</dd> <dd>  <input type="hidden" id="tillDate' + i + '" name="tillDate" value= "' + el.tillDate + '"/> <input type="hidden" id="bookUid' + i + '" name="bookUid" value= "' + el.book.bookUid + '"/> <input type="hidden" id="libraryUid' + i + '" name="libraryUid" value= "' + el.library.libraryUid + '"/><btn class="btn btn-success btn-return-book"> Вернуть </btn> </dd></dl>');
                    } else {
                        $('.books-reservations').append('Нет доступных резерваций!');
                    }
                });

            }
        });

    });
});

$(document).on("click", ".books-reservations  .btn-return-book", function (e) {
    e.preventDefault();
    let i = $('.books-reservations  .btn-return-book').index(this);
    let bookUid = $(`input[id="bookUid${i}"]`).val();
    let libraryUid = $(`input[id="libraryUid${i}"]`).val();
    let tillDate = $(`input[id="tillDate${i}"]`).val();

    console.log(bookUid);
    console.log(libraryUid);
    console.log(tillDate);


    $.get("http://localhost:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function (jwt) {

        var token = jwt;
        $.ajax({
            url: 'http://localhost:8080/api/v1/reservations/' + bookUid + '/' + libraryUid + '/' + tillDate,
            type: 'POST',
            dataType: 'json',
            crossDomain: true,
            headers: {'token': token},
            success: function (data) {
                console.log("dfdf");
                console.log(data);

            }
        });


    });
});


    
