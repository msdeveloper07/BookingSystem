
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

var indexCounter = 1;
  
 $(document).ready(function() {
  $('#add_new_items').click(function() {
    
      var numItems = $('#box_body1 .holder_div').length;
      
      var template = $('#add_new_template').html();
      if(numItems!=indexCounter)
      {
         template = template.replace(/index/g, numItems+1);
                 // console.log(numItems);
      }
      else
      {
        template = template.replace(/index/g, indexCounter+1);  
      }
      
      $('#box_body1').append(template);
      
      indexCounter++;
      
    });         
 });
 
 
function removeItem(index){

    $("#div_repeat_"+index).remove();
}



//$(document).ready(function() {
//  $('#add_new_items').click(function() {
// 
//      var numItems = $('#box_body1 .holder_div').length;
//    
//      var template = $('#add_new_template').html();
//      console.log(template);
//      template = template.replace(/index/g, numItems+1);
//     
//      $('#box_body1').append(template);
//      console.log("dsdada");
//      
//    });  
//       
// });

//$(function(index)
//{
//     $('#date_times').datepicker({minDate : 2,dateFormat: 'yy-mm-dd'});
////     $('#time').timepicker();
//
//$('.timepicker').timepicker({ timeFormat: 'h:mm:ss p' });
//}); 

$(document).ready(function(){
   // $('.timepicker').timepicker({ timeFormat: 'h:mm:ss' });
});