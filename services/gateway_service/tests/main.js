
$('.btn-token').click(function (e) { 
  e.preventDefault();
  //$('.my-text').load('http://193.233.164.116:8080/api/v1/authorize?profile=admin&email=admin@admin.ru');

  var token2;
  $.get("http://193.233.164.116:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function(jwt){
    
    var token = jwt;
   
    console.log(token);

   
    
    $.ajax({
          url: 'http://193.233.164.116:8080/api/v1/reservations',
          type: 'GET',
          dataType: 'json',
          headers: {'token': token},
          success: function(data) {
            console.log("Кефтемек");
            console.log(data);
            let i=0;
            data.forEach(function(el){
                if(el.status == "RENTED"){
                  $('.books-reservations').append('<dl><dt>'+ el.book.name +'</dt> <dd>'+ el.book.author +'</dd> <dd>  <input type="hidden" id="reservationUid'+i+'" name="reservationUid" value= "'+el.reservationUid +'"/><btn class="btn btn-success btn-return-book"> Вернуть </btn> </dd></dl>');
                }

             
            });  
                  
          }
      }); 


  });

 
  console.log(token2);
});

$(document).on( "click", ".books-reservations  .btn-return-book", function(e) {
  e.preventDefault();
  let i = $('.books-reservations  .btn-return-book').index(this);
  let reservationUid = $(`input[id="reservationUid${i}"]`).val();

  console.log(reservationUid);


});


    //   $.ajax({
    //     url: 'http://193.233.164.116:8080/api/v1/authorize?profile=admin&email=admin@admin.ru',
    //     type: 'GET',
    //     dataType: 'json',
    //     success: function(token) {
    //             console.log("кефтеме:" + token);
    //             console.log("кефтеме:");
    //     }
    // }); 


    
