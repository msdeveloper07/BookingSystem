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
// $(document).ready(function()
// {
//    $("#supplier_type_id").change(function()
//    {
//        alert("The text has been changed.");
//    });
//}); 
//die();
function removeItem(index){

    $("#div_repeat_"+index).remove();
}


//$(document).ready(function(){
//    $("#user_id").mouseenter(function()
//    {
//        alert("you enter the user group");
//    });
//});
function myFunction(){
    alert("working");
}

var supplierData = null;
  $(document).ready(function(){
    $.getJSON( "/ajax/getsupplierItemInfo/", function( data ) {
        supplierData = data;
     });
     
//     console.log(supplierData);
 }); 
    
  
  function loadItems(value, index)
  {
      $.each(supplierData,function(k,v){
          if(v.supplier_type_id==value)
          {
              var html = '<option value="">Please Select</option>';
              $.each(v.items,function(m,i){
                  html += '<option value="'+i.supplier_item_id+'">'+i.supplier_item_name+'</option>';
              });
              
              var target = "supplier_item_id_"+index;
              $('#'+target).html('');
              $('#'+target).append(html);
          }
          else{
           //   console.log("not right");
          }
      });
     
      
  }

     

    
            
  
 
 $(document).ready(function(){
 
    $('#supplier_item_id').change(function(){
       
          //alert($(this).val());
       var val = $(this).val();
     if(val!=''){
        $.getJSON( "/ajax/getsupplierItemPriceInfo/"+val, function( data ) {
            $('#cost').val(data[0].supplier_cost);
         
         });
          
        }  
           
    }); 
       
    }); 

     $(document).ready(function(){

    $('#location_id').change(function(){
      $("#location_id").chosen();  
      
    });
});

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
 
 
 
  $(document).ready(function(){

    $('#supplier_type_id').change(function(){
      $("#supplier_type_id").chosen();  
          //alert($(this).val());
       var val = $(this).val();
     
        $.getJSON( "/ajax/getsupplierSubTypeInfo/"+val, function( data ) {
           $('#supplier_sub_type').empty();
             var opt = $('<option>'); 
           opt.val();
            opt.text("Please Select");
             $('#supplier_sub_type').append(opt); 
           $.each(data,function(index,value){
               
               var opt = $('<option>'); 
               opt.val(value.supplier_type_id);
            opt.text(value.supplier_type);
            
            $('#supplier_sub_type').append(opt); 
           });
         });
          
       
           
    }); 
    }); 