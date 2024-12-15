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
    <form  role="form" action='/mailinglistsubscribers/{{$mailinglist_subscriber->mailinglist_subscriber_id}}' name='user_form' id='user_form' method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_method" value="PUT">


         <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Mailing List Subscriber</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Mailing List Subscriber Name</label>
                            <input type="text" placeholder="Mailing List Subscriber Name" id="mailingList_subscriber_name" name="mailinglist_subscriber_name" class="form-control required" value="{{$mailinglist_subscriber->mailinglist_subscriber_name}}">
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Mailing List Subscriber Email</label>
                            <input type="email" placeholder="Mailing List Subscriber Email" id="mailingList_subscriber_email" name="mailinglist_subscriber_email" class="form-control required" value="{{$mailinglist_subscriber->mailinglist_subscriber_email}}">
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InputTitle">Mailing List</label>
                           <select class="form-control" multiple="multiple" id="mailingLists" name="mailinglists[]">
                                <option value="">Please Select</option>
                                @foreach($mailinglist as $ml)
                                <option {{in_array($ml->mailinglist_id,$mailinglists)?'selected="selected"':''}} value="{{$ml->mailinglist_id}}">{{$ml->mailinglist_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    </div>
              </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop