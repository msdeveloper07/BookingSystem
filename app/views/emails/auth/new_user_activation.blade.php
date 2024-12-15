<html>
    <head>
        <title>New Registration</title>
    </head>
    <body>
        <p>Dear {{$name}},</p>
        <p>Your account has been created and you may need to activate your account by click on following link:</p>
        <p><a href="{{url()}}/activate/{{$activation_code}}">Activate</a></p>
        <p>&nbsp;</p>
        <p>Regards</p>
        <p><a href="{{url()}}">Leads Management</a></p>
    </body>
    
</html>