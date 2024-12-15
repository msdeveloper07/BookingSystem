@extends('layouts.adminTemplate')

@section('content')


@if ( $errors->count() > 0 )

<p>The following errors have occurred:</p>
<ul>
    @foreach($errors->all() as $m)
    <li><span class="text-danger">{{$m}}</span></li>
    @endforeach
</ul>

@endif

<div class="row">
        <div class="col-md-6">
            <form  role="form" action='/message_folders/{{$message_folder->message_folder_id}}' name='message_folder_form' id='message_folder_form' method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="_method" value="PUT">
            
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="form-group">
                        <label for="InputTitle">Title</label>
                        <input type="text" placeholder="Title for the message folder" id="message_folder_title" name="message_folder_title" class="form-control required" value="{{$message_folder->message_folder_title}}">
                    </div>
                    
                  
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
           </form>  
            
      
            
            
       </div>

</div>

@stop