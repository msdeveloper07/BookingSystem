<html>
    <head>
        <title>Follow Up Email</title>
    </head>
    <body>
        <p>Following are the leads that need follow ups:</p>
        
        <table border='1px' style="border-collapse: collapse" cellpadding="3px">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Lead Title</th>
                    <th>Department</th>
                    <th>Sub Department</th>
                    <th>Follow Up On</th>
                    <th>Company</th>
                    <th>Contact Person</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1 ;
                $date = date('Y-m-d');
                ?>
                
                @foreach($leads as $l)
                
                <?php if($l->follow_up_on < $date)
                    $style = "background-color: #f2dede";
                    else
                     $style = "";   
                    ?>
                
                <tr style="{{$style}}">
                    <td>{{$i}}</td>
                    <td>{{$l->lead_title}}</td>
                    <td>{{$l->department->department_title}}</td>
                    <td>{{@$l->subdepartment!=''?$l->subdepartment->department_title:""}}</td>
                    <td>{{ZnUtilities::format_date($l->follow_up_on,'2')}}</td>
                    <td>{{$l->company->company_title}}</td>
                    <td>{{$l->contact_person}}</td>
                    <td>{{url().'/leads/'.$l->lead_id}}</td>
                </tr>
                <?php $i++;?>
                @endforeach
            </tbody>
        </table>
        
      


        <p>Regards</p>
        <p><a href="{{url()}}">Leads Management</a></p>
    </body>
    
</html>