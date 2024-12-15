<html>
    <head>
        <title>Reset Password</title>
    </head>
    <body>
        <p>Dear {{$name}},</p>
        <p>You can click on following link to set your password:</p>
        <p><a href="{{url()}}/password/reset/{{$reset_code}}">Reset</a></p>
        <p>&nbsp;</p>
        <p>Regards</p>
        <p><a href="{{url()}}">Leads Management</a></p>
    </body>
    
</html>