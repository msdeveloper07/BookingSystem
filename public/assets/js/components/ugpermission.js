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
//    $("#user_group_id").change(function()
//    {
//        alert("The text has been changed.");
//    });
//}); 
function demo(){

    alert("working");
}


//$(document).ready(function(){
//    $("#user_id").mouseenter(function()
//    {
//        alert("you enter the user group");
//    });
//});

$(document).ready(function(){

    $('#user_id').change(function(){
      $("#user_id").chosen();  
          //alert($(this).val());
       var val = $(this).val();
     
        $.getJSON( "/ajax/getItemInfo/"+val, function( data ) {
           $('#name').val(data[0].name);
           $('#email').val(data[0].email);
           $('#user_group').val(data[0].user_group_id);
         $('#status').val(data[0].user_status);
         $('#image').val(data[0].images.file_name);
             
             
          });
    }); 
    }); 
    
    $('#user_group_id').change(function () {
        var value = $(this).val();
    var location = "/usergrouppermission/" + ($(this).val()!=''?$(this).val():'0') + "/edit";
    window.location = location;
});
