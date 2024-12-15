<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">

    <title>{{$page_title}}</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/prettify.min.css">
    <link rel="stylesheet" href="/assets/css/morris.css">
    <link rel="stylesheet" href="/assets/css/redactor.css">
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/css/normalize.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/responsive.css" rel="stylesheet">
    <link href="/assets/css/fonts.css" rel="stylesheet">
     <!-- bootstrap wysihtml5 - text editor -->
     <link href="/assets/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />

    <!--[if lt IE 9 &!(IEMobile)]><link href="css/bootstrap-ie7.css" rel="stylesheet" type="text/css" /><![endif]-->
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

      @include('layouts.navbar')


      @include('layouts.left')
            <div class="main-section">
            <div class="container-page">
                <?php
//                ZnUtilities::pa($submenus);
//                die;
                ?>
                
                @if(isset($submenus)&&(count($submenus)>0))
                    {{ZnUtilities::create_submenus($submenus);}}
                @endif
                 @include('layouts.notifications')
      @yield('content')
      @include('layouts.converter')
            
      </div><!--/.container-page-->
      </div><!--/.main-section-->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/assets/js/jquery.min.js"></script>
    {{ZnUtilities::load_js_files();}}
    {{ZnUtilities::load_js();}}
    <script src="/assets/js/jquery-ui.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="/assets/js/main.js"></script>
  </body>
</html>
