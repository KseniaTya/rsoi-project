
$(document).ready(function () {


      $.ajax({
        url: 'http://193.233.164.116:8080/api/v1/authorize?profile=admin&email=admin@admin.ru',
        type: 'GET',
        dataType: 'json',
        success: function(token) {
           
                console.log("кефтеме:" + token);
                console.log("кефтеме:");
               
                    // data.fields.forEach(function(field){
                    //      $(`input[name="${field}"]`).addClass("border-danger");
                    //      $(`textarea[name="${field}"]`).addClass("border-danger");
                    //      $(`select[name="${field}"]`).addClass("border-danger");
                    // });  

                
                
                //$('.msg').removeClass('none').text(data.message); //
            
        }



    }); 

      //console.log(yourVariable);

    // $.get("http://193.233.164.116:8080/test?id_test=get_books.php", function(data){
    //     console.log(data);
    
       
    //   });


      
 
});