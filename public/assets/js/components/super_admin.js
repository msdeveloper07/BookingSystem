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

$(document).ready(function(){

    $('#users_id').chosen({width:'95%'}).change(function(){
        //console.log('here');
        //alert($(this).val());
        var val = $(this).val();
     
        $.getJSON( "/ajax/getItemInfo/"+val, function( data ) {
              $('#name').val(data[0].name);
             
//              var ext_price = data[0].price.price * $('#quantity').val();
//
//            
//            $('#ext_price').val(ext_price);
              
          });
    }); 
    
   
    
});

//$(document).ready(function()
//{
//    $("#user_id").click(function()
//    {
//        $(this).hide();
//    });
//});
 $(document).ready(function()
 {
    $("#user_id").change(function()
    {
        alert("The text has been changed.");
    });
}); 