
$('.btn-token').click(function (e) { 
  e.preventDefault();
  $('.my-text').load('http://193.233.164.116:8080/api/v1/authorize?profile=admin&email=admin@admin.ru');

  $.get("http://193.233.164.116:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function(data, status){
    alert("Данные: " + data + "\nСостояние: " + status);
  });
  
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


    
