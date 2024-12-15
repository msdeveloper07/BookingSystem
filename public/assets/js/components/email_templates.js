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
  
   $("#move_to").change(function(){
      
     if($("input:checkbox:checked").length>0) 
     {
           $("#actions_form").submit();
      }  
     else{
         $('#move_to').val('');
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
    
    
        
    $("#template_parent_id").change(function(){
        var message_id = $("#template_parent_id").val();
        if(message_id!=''){
            $.getJSON( "/ajax/getMessageContent/"+message_id, function( data ) {
                $('#original_message_container').show();
                if(data.length>0){
                  $.each( data, function( key, val ) {
                     $('#message_content').html(val.content); 
                  });
                }
            });
         }
    });
    
//    
//    var $selected = $('input[name="email_type"]:checked');
//        if($selected.length == 0) {
//           console
//        } else {
//           var whichOne = $selected.val();
//           // do stuff here
//        }
//        
        $('input[name="email_type"]').click(function(){
                if ($(this).is(':checked'))
                {
                  var val = $(this).val();
                  if(val=='reply')
                  {
                      $('#messages_list').show();
                      $('#template_parent_id').prop('required',true);
                  }
                  if(val=='new')
                  {
                      $('#messages_list').hide();
                      $('#template_parent_id').removeAttr('required');
                  }
                  
                }
              });

    
    
});


