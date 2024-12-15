<div class="row">
    <div class="col-md-10"> 
        @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            {{ Session::get('success_message') }}
        </div>
        @endif

        @if(Session::has('error_message'))
        <div class="alert alert-danger alert-dismissable">
        <i class="fa fa-ban"></i>
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
             <b>Error!</b> {{ Session::get('error_message') }}
        </div>
        @endif

        @if(Session::has('warning_message'))
        <div class="alert alert-warning alert-dismissable">
            <i class="fa fa-warning"></i>
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <b>Alert!</b> {{ Session::get('warning_message') }}
         </div>
        @endif

        @if(Session::has('info_message'))
        <div class="alert alert-info alert-dismissable">
        <i class="fa fa-info"></i>
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <b>Information!</b>{{ Session::get('info_message') }}
        </div>
        @endif
    </div>
</div>
