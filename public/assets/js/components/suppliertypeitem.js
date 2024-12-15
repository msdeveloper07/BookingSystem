
// $(document).ready(function() {
//  $('#more').click(function() {
//      var numItems = $('#box_body1 .holder_div').length;
//      var template = $('#add_new_template').html();
//      template = template.replace(/index/g, numItems+1);
//      $('#box_body1').append(template);
//      console.log("dsdada");
//      
//    });         
// });
// 
 
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
           
                    
         /*           
          
                       var html = '<div id="div_repeat"><div class="row"><div class="col-md-4"><div class="form-group"><label for="InputTitle">Supplier Type</label><select class="form-control" name="supplier_type_id[]" id="supplier_type_id">';
     html += '<option value="">Select Supplier Type</option><option value="2">Hotel </option><option value="4">AirLine </option></select></div></div><div class="col-md-4"><div class="form-group"><label for="InputTitle">Supplier Item</label>';
     html += '<input placeholder="Enter Supplier Item" id="supplier_item_name" name="supplier_item_name[]" class="form-control" value="" type="text"></div></div></div></div>';          
     $('#append_data').append(html);
                  */
                 
                 
                 var supplierSubType = null;
  $(document).ready(function(){
    $.getJSON( "/ajax/getsupplierSubTypesInfo/", function( data ) {
        supplierSubType = data;
     });
     
     console.log(supplierSubType);
 }); 
    
    
   

  function loadSubTypes(value, index)
  {
      $.getJSON( "/ajax/getsupplierSubTypeInfo/"+value, function( data1 ) {
       $('#supplier_sub_type_id_'+index).empty();
  var html = '<option value="">Please Select</option>';
         $.each(data1,function(m,i){
             html += '<option value="'+i.supplier_type_id+'">'+i.supplier_type+'</option>';
         });
               
               var target = "supplier_sub_type_id_"+index;
              $('#'+target).html('');
              $('#'+target).append(html);
           });
     
     
    
  }
//  function loadSubType(value)
//  {
//      $.getJSON( "/ajax/getsupplierSubTypeInfo/"+value, function( data1 ) {
//       $('#supplier_sub_type_id').empty();
//  var html = '<option value="">Please Select</option>';
//         $.each(data1,function(m,i){
//             html += '<option value="'+i.supplier_type_id+'">'+i.supplier_type+'</option>';
//         });
//               
//               var target = "supplier_sub_type_id";
//              $('#'+target).html('');
//              $('#'+target).append(html);
//           });
//     
//     
//    
//  }
//  
//            $(document).ready(function(){
//
//    $('#supplier_type_id').change(function(){
//    
//          //alert($(this).val());
//       var val = $(this).val();
//     
//        $.getJSON( "/ajax/getsupplierSubTypeInfo/"+val, function( data ) {
//           $('#supplier_sub_type_id').empty();
//           $.each(data,function(index,value){
//               
//               var opt = $('<option>'); 
//               opt.val(value.supplier_type_id);
//            opt.text(value.supplier_type);
//            
//            $('#supplier_sub_type_id').append(opt); 
//           });
//         });
//              
//    }); 
//    }); 