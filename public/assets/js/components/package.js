$(document).ready(function(){
  $("#bulk_action").change(function(){
     if($("input:checkbox:checked").length>0) 
     {
        if( $('#bulk_action').val()=="delete")
        {
          var confirm_action =  confirm("Do you really want to delete selected rows");
          if(confirm_action==true){
              $("#actions_form").submit();
          }
          else
           {
                $('#bulk_action').val('');
           }
              
        }
        else
        {    
            $("#actions_form").submit();
        }    
      }  
     else{
         $('#bulk_action').val('');
         alert("No row selected");
     }
  });
});


//Toggle checkboxes state
$(document).ready(function(){   
    
    
    // add multiple select / deselect functionality
    $("#checkall").click(function () {
          $('.check').prop('checked', this.checked);
    });

    
 
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    $(".check").click(function(){
 
        if($(".check").length == $(".check:checked").length) {
            $("#checkall").prop("checked", "checked");
        } else {
            //$("#checkall").removeAttr("checked");
            $("#checkall").prop("checked",'');
        }
 
    });
});


function confirm_delete(root_url,id)
{
   // alert(id);
    var confirm_action =  confirm("Do you really want to delete this user");
    if(confirm_action==true){
      location.href=root_url+'/users/delete/'+id;
    }
    
}

$(function()
{
    $('#date_from').datepicker({minDate : 2,dateFormat: 'yy-mm-dd'});
     $('#date_to').datepicker({minDate : 3,dateFormat: 'yy-mm-dd'});
}); 


$(function() {
    $('.image').hover(function() {
        
        $(this).stop().animate({width: '60px'}, 'fast')
        $('.image').not(this).stop().animate({width: '20px'}, 'fast');
    });
    });


$(document).ready(function(){

    $('#location_from').change(function(){
      $("#location_from").chosen();  
          //alert($(this).val());
       var val = $(this).val();
     
        $.getJSON( "/ajax/getLocationInfo/"+val, function( data ) {
           $('#location_to').empty();
           $.each(data,function(index,value){
               
               var opt = $('<option>'); 
               opt.val(value.location_id);
            opt.text(value.location_name);
            
            $('#location_to').append(opt); 
           });
         });
          
       
           
    }); 
    }); 
    
    $(document).ready(function(){

    $('#location_to').change(function(){
      $("#location_to").chosen();  
      
    });
});

function removeImageFromGallery(package_image_id)
{
    $.ajax({
        url: "/ajax/removeImageFromGallery/"+package_image_id, 
        success: function(result){
            $("#package_image_"+package_image_id).remove();
        }
    });     
         
}

//$(document).ready(function(){
//    $(".image").mouseenter(function(){
//        $("p").css("background-color", "yellow");
//    });
//    $(".image").mouseleave(function(){
//        $("p").css("background-color", "lightgray");
//    });
//});