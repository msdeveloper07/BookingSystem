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
    $('#date_from').datepicker({dateFormat: 'M dd, yy'});
    //$('#date_to').datepicker({dateFormat: 'M dd, yy'});
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

function amount(discount_type){
    if(discount_type!=''){
   // alert(discount_type);
   var amount=$("#package_cost").val();
   var discount=$("#discount").val();
   var price ="";
   if(discount_type==0){
       price=parseFloat(amount-discount);
   }
   else{
      price=parseFloat(amount*discount/100);
      price=parseFloat(amount-price);
   }
   $("#quoted_cost").val(price.toFixed(2));
}
}

 $(document).ready(function(){
    
   var price='';
     $( "#discount" ).keyup(function() {
           var amount=$("#package_cost").val();
   var cost=$("#quoted_cost").val();
   var discount_type=$("#discount_type").val();
        var discount=$("#discount").val();
      
  if(amount!='' && cost!='' &&discount_type!=''){
      if(discount_type==0){
       price=parseFloat(amount-discount);
   }
   else if(discount_type==1){
      price=parseFloat(amount*discount/100);
      price=parseFloat(amount-price);
   }
   $("#quoted_cost").val(price.toFixed(2));
}
  });
});
     
   

 $(document).ready(function(){
                  
                 function search(){
 
                      var title=$("#search").val();
 
                      if(title!=""){
                       
                         $.ajax({
                            type:"post",
                            url:"consearch",
                            data:"title="+title,
                            success:function(data){
                                
                                console.log(data);
                                
                                $("#result").html(data);
                                $("#search").val("");
                             }
                          });
                      }
                       
 
                      
                 }
 
                  $("#button").click(function(){
                     search();
                  });
 
                  $('#search').keyup(function(e) {
                     if(e.keyCode == 13) {
                        search();
                      }
                  });
            });
            
    
$(document).ready(function(){

    $('#location_from').change(function(){
        
        
      $("#location_from").chosen();  
         
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
    
    
//   $(document).ready(function(){
//
//    $.getJSON( "/ajax/getsubtype", function( data ) {
//         $('.sub_type').change(function(){
//                
//                 $.each(data,function(index,value){              
//              $('.sub'+value.supplier_type_id).hide();
//           });
//            var val = $(this).val();
//                $('.sub'+val).show();
//                 
//         });
//               
//    }); 
//    }); 
//    
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
 