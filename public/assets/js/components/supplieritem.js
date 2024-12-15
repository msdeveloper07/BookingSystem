
 $(document).ready(function() {
  $('#more').click(function() {
      var numItems = $('#box_body1 .holder_div').length;
      var template = $('#add_new_template').html();
      template = template.replace(/index/g, numItems+1);
      $('#box_body1').append(template);
      console.log("dsdada");
      
    });         
 });
 
 
 
           
                    
         /*           
          
                       var html = '<div id="div_repeat"><div class="row"><div class="col-md-4"><div class="form-group"><label for="InputTitle">Supplier Type</label><select class="form-control" name="supplier_type_id[]" id="supplier_type_id">';
     html += '<option value="">Select Supplier Type</option><option value="2">Hotel </option><option value="4">AirLine </option></select></div></div><div class="col-md-4"><div class="form-group"><label for="InputTitle">Supplier Item</label>';
     html += '<input placeholder="Enter Supplier Item" id="supplier_item_name" name="supplier_item_name[]" class="form-control" value="" type="text"></div></div></div></div>';          
     $('#append_data').append(html);
                  */