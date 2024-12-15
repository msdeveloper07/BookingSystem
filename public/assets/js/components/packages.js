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

function removeItem(index){

    $("#div_repeat_"+index).remove();
}



function myFunction(){
    alert("working");
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

function amount(total_cost){
    if(adult_cost!='' || child_cost!=''){
   // alert(discount_type);
   var adult_cost=$("#adult_cost").val();
   var child_cost=$("#child_cost").val();
    var number_of_adults=$("#number_of_adults").val();
   var number_of_children=$("#number_of_children").val();
   var total_cost ="";
   if(adult_cost!='' || adult_cost!='0'){
            total_adult_cost = adult_cost * number_of_adults;
        }
        if(child_cost!='' || child_cost!='0'){
            total_child_cost = child_cost * number_of_children;
        }
        total_cost = total_child_cost + total_adult_cost ; 
   $("#total_cost").val(total_cost.toFixed(2));
}
}

 $(document).ready(function(){
    
   var package_total_cost='';
     $( "#child_cost , #adult_cost" ).keyup(function() {
            var adult_cost=$("#adult_cost").val();
   var child_cost=$("#child_cost").val();
    var number_of_adults=$("#number_of_adults").val();
   var number_of_children=$("#number_of_children").val();
  
   if(adult_cost!='' || adult_cost!='0'){
            total_adult_cost = adult_cost * number_of_adults;
        }
        
        if(child_cost!='' || child_cost!='0'){
            total_child_cost = child_cost * number_of_children;
        }
        package_total_cost = total_child_cost + total_adult_cost ; 
   $("#total_cost").val(package_total_cost.toFixed(2));
  });
});



//$(document).ready(function(){
//
//    $.getJSON( "/ajax/getsubtype", function( data ) {
//         $('.sub_type').change(function(){
//                   $(".sub_type").chosen();
//                 $.each(data,function(index,value){              
//              $('.sub'+value.supplier_type_id).hide();
//            
//           });
//            var val = $(this).val();
//            
//            alert(val);
//            
//             $('.sub'+val).show();
//             });
//               
//    }); 
//    }); 

$(document).ready(function(){
  $('.sub_type').change(function(){
         var val = $(this).val();
    $.getJSON( "/ajax/getsubtypeinfo/"+val, function( data ) {
       
       console.log(data);
       
        $.each(data,function(index,value){              
              $('.sub'+value.supplier_type_id).hide();
             });
         
        $('.sub'+val).show();
        
             });
               
    }); 
    }); 
   

 