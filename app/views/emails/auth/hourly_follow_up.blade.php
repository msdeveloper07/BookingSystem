<html>
    <head>
        <title>Follow Up Email</title>
    </head>
    <body>
        <p>Following are the leads that need follow ups:</p>
        
        <table border='1px' style="border-collapse: collapse" cellpadding="3px">
            <thead>
                <tr>
                    
                    <th>Lead Title</th>
                    <th>Department</th>
                    <th>Company</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Office Tel</th>
                    <th>Mobile</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $date = date('Y-m-d');
                ?>
                
                
                <?php 
                    
                    if($l->follow_up_on < $date)
                        $style = "background-color: #f2dede";
                    else
                        $style = "";   
                    ?>
                
                <tr style="{{$style}}">
                  
                    <td>{{$l->lead_title}}</td>
                    <td>{{$l->department->department_title}}</td>
                    <td>{{$l->company->company_title}}</td>
                    <td>{{$l->contact_person}}</td>
                    <td>{{$l->company->company_email}}</td>
                    <td>{{$l->company->office_tel}}</td>
                    <td>{{$l->company->cell_phone}}</td>
                    <td>{{url().'/leads/'.$l->lead_id}}</td>
                </tr>
                
            </tbody>
        </table>
        
      


        <p>Regards</p>
        <p><a href="{{url()}}">Leads Management</a></p>
    </body>
    
</html>