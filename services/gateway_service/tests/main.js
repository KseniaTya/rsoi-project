
$('.btn-token').click(function (e) { 
  e.preventDefault();
  $('.my-text').load('http://193.233.164.116:8080/api/v1/authorize?profile=admin&email=admin@admin.ru');

  var token2;
  $.get("http://193.233.164.116:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function(jwt){
    // console.log(data);
    var token = jwt;
    token2 = jwt;
    console.log(token);
    console.log("======================"+token2);
    // token2 = data.toString();

    $.get("http://193.233.164.116:8080/api/v1/api/v1/libraries/83575e12-7ce0-48ee-9931-51919ff3c9ee/books?page=1&size=25&showAll=true", function(data){

    console.log(data);
    });
    

    // $.ajax({
    //       url: 'http://193.233.164.116:8080/api/v1/api/v1/libraries/83575e12-7ce0-48ee-9931-51919ff3c9ee/books?page=1&size=25&showAll=true',
    //       type: 'GET',
    //       dataType: 'json',
    //       //async: false,
    //       data: {
    //         jwt: token2
    //     },
    //       success: function(data) {
    //               console.log(data);
    //               console.log("кефтеме:");
    //       }
    //   }); 


  });

 
  console.log(token2);
  
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


    
