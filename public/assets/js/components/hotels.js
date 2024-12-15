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
 $('#due_date').datepicker({minDate : 1,dateFormat: 'yy-mm-dd'});


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


var indexCounter = 1;
  
 $(document).ready(function() {
  $('#more').click(function() {
      
      var numItems = $('#box_body1 .holder_div').length;
      var template = $('#add_new_template').html();
      if(numItems!=indexCounter)
      {
         template = template.replace(/index/g, indexCounter+1);
      }
      else
      {
        template = template.replace(/index/g, numItems+1);  
      }
      
      $('#box_body1').append(template);
      
      indexCounter++;
      
    });         
 });

function suppliertype(value, index)
  {

        $.getJSON( "/ajax/getsupplierSubTypeInfo/"+value, function( data ) {
           $('#supplier_sub_type_'+index).empty();
             var opt = $('<option>'); 
           opt.val();
            opt.text("Please Select");
             $('#supplier_sub_type_'+index).append(opt); 
           $.each(data,function(i,value){
               
               var opt = $('<option>'); 
               opt.val(value.supplier_type_id);
            opt.text(value.supplier_type);
            
            $('#supplier_sub_type_'+index).append(opt); 
           });
         });
  
    }
    
    
    
    
    
    
  function supplierInfo(value, index)
  {
        $.getJSON( "/ajax/getsupplierInfo/"+value, function( data ) {
            
          
           $('#supplier_id_'+index).empty();
             var opt = $('<option>'); 
           opt.val();
            opt.text("Please Select");
             $('#supplier_id_'+index).append(opt); 
      
           $.each(data,function(i,value){
               
                opt = $('<option>'); 
             
               opt.val(value.supplier_id);
            opt.text(value.supplier_name);
            
            $('#supplier_id_'+index).append(opt); 
           });
         });
          
       
           
    }
   
    function supplierTypeBookingSuppliers(id, value, index)
  {
      

        
        $.getJSON( "/ajax/getsupplierTypeBookingSuppliers/"+id+ "/" +value, function(data ) {
            
            
           $('#supplier_id_'+index).empty();
             var opt = $('<option>'); 
           opt.val();
            opt.text("Please Select");
             $('#supplier_id_'+index).append(opt); 
             for(var j =0;j<data.length;j++){
           $.each(data[j].supplier_info,function(i,value){
               
               var opt = $('<option>'); 
               opt.val(value.supplier_id);
            opt.text(value.supplier_name);
            
            $('#supplier_id_'+index).append(opt); 
           });
       }
         });
  
    }
 
function supplierItems(id, value, index)
  {
      
    $.getJSON( "/ajax/getsupplierItems/"+id+ "/" +value, function(data ) {
            
        console.log(data);
         
           $('#supplier_item_id_'+index).empty();
             var opt = $('<option>'); 
           opt.val();
            opt.text("Please Select");
             $('#supplier_item_id_'+index).append(opt); 
             for(var j =0;j<data.length;j++){
           $.each(data[j].supplier_items,function(i,value){
               
               var opt = $('<option>'); 
               opt.val(value.supplier_type_item_id);
            opt.text(value.supplier_item_name);
            
            $('#supplier_item_id_'+index).append(opt); 
           });
       }
         });
  
    }
    
    function removeItem(index){

    $("#div_repeat_"+index).remove();
}


 function full(value)
{
    if(value=='Full&Final')
    {
        $("#due").hide();
    }
    else{
        $("#due").show();
    }
   
}
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function sort_results(value)
{
      
    if(value=='canceled')
    {
  
     
        location.href = '/sort/bookings/'+'canceled';
    }
    else if(value=='process')
    {
        location.href = '/sort/bookings/'+0 +"/" +'process';
    }
    else
    {
         location.href = '/sort/bookings/';
    }
    
    // +1 belong to cancel booking
    // +2 belong to processing booking
} 

$(document).ready(function () {
  //called when key is pressed in textbox
  $(".cost").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });
});