$('.btn-libraries').click(function (e) { 
    e.preventDefault();
    $('.libraries').text(' ');
    //$('.my-text').load('http://193.233.164.116:8080/api/v1/authorize?profile=admin&email=admin@admin.ru');
  ///////
  
    $.get("http://193.233.164.116:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function(jwt){
      
      var token = jwt;
     
      console.log(token);
  
     
      
      $.ajax({
            url: 'http://193.233.164.116:8080/api/v1/libraries',
            type: 'GET',
            dataType: 'json',
            headers: {'token': token},
            success: function(data) {
              console.log("Кефтемек");
              console.log(data);
              let i=0;
              data.items.forEach(function(el){               
                    $('.libraries').append('<dl><dt>'+ el.name +'</dt> <dd>  <btn class="btn btn-success btn-info-libraries"> Перейти </btn> </dd></dl>');
              });  
                    
            }
        }); 
  
  
    });
  
   
    
  });

  $(document).on( "click", ".libraries  .btn-info-libraries", function(e) {
    e.preventDefault();
  
    $.get("http://193.233.164.116:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function(jwt){
      
    var token = jwt;
    $.ajax({
      url: 'http://193.233.164.116:8080/api/v1/libraries/83575e12-7ce0-48ee-9931-51919ff3c9ee/books?page=1&size=25&showAll=true',
      type: 'GET',
      dataType: 'json',
      headers: {'token': token},
      success: function(data) {
      
        console.log(data);
              
      }
    }); 
  
  
    });
  });
  