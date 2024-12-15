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
         if(( $('#bulk_action').val()=="viewUnread")||( $('#bulk_action').val()=="viewUnactioned")||( $('#bulk_action').val()=="viewActioned")){
             $("#actions_form").submit();
         }
         else{
            $('#bulk_action').val('');
            alert("No row selected");
         }
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
    
  $("#unlink_lead_button").click(function (){
     
    var c = confirm("Are you sure you would like to unlink this lead?")
    if(!c)
    {
        return false;
    }
  });  
    
    // add multiple select / deselect functionality
    $("#checkall").click(function () {
          $('.check').prop('checked', this.checked);
    });

    
    $("#search_button").click(function () {
          $("#actions_form").submit();
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
    var confirm_action =  confirm("Do you really want to delete this message");
    if(confirm_action==true){
      location.href=root_url+'/companies/delete/'+id;
    }
    
}

function choose_template(template_id)
{
     $.getJSON( "/ajax/getTemplateContent/"+template_id, function( data ) {
        //        console.log(data.email_template_id)
                $('#subject').val(data.email_template_title);
                CKEDITOR.instances['content'].setData(data.content);
                
                if(data.attachment!=''){
                    var attachment_array = data.attachment.split('/attachments/');
                    var name_array = attachment_array[1].split('-');
                    
                    var ext_array = data.attachment.split('.');
                    var len = ext_array.length;
                    var extension = ext_array[len-1];
                    
                     name_array.splice('0',1);
                    var file_name = name_array.join(); 
                    
                    $("#prev_upload tbody").append("<tr id='45sDSDssw' class='document_files'><td data-title=File Type'><img class='property_image' src='/images/attachment.png' height='22px'/><span class='file-type'>"+extension+"</span> </td><td data-title='Filename'>"+file_name+"</td><td data-title='Remove File'><a class='remove' href='javascript:void(0);' onclick=javascript:remove_div('45sDSDssw'); ><span class='glyphicon glyphicon glyphicon-remove'></span> Remove</a><input type='hidden' name='specs_location[]' value='"+data.attachment+"' /></td></tr>");
                    
                    $('#files_table').show();
                }
                
                $('#compose-modal').modal('hide');
                
            });
}

function show_templates_div()
{
    $("#templates_div").toggle();
    
}

function show_forward_field(div_id)
{
    $('#'+div_id).toggle();
}

//function resizeIframe(iframe) {
//    console.log('here');
//    iframe.height = iframe.contentWindow.document.body.scrollHeight + "px";
//  }