@extends('layouts.adminTemplate')

@section('content')

@if(isset($page_title))
<h2 class="page_header">{{$page_title}}</h2>
@endif

<form class="form-inline" action="/mailinglistsubscriberActions" method="post" name="actions_form" id="actions_form">

    <div class="box box-danger">


        <div class="box-body">
            <div class="row">

                <div class="col-md-4">
                    Actions
                    <div class="form-group">
                        <select id="bulk_action" name="bulk_action" class="form-control" placeholder="Select Action"  >
                            <option value="">Select An Action</option>
                            <option value="delete">Delete Selected Mailing List Subscriber</option>
                        </select>
                    </div>

                </div>


                <div class="col-md-5">

                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                    <div class="input-group">
                        <input type="text" value="<?php if (isset($search)) {echo $search;} ?>" class="form-control" name="search" id="search" placeholder="Search Mailing Subscriber Lists">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-default btn-flat">Find Mailing List Subscriber</button>
                        </span>
                    </div>

                </div>

                <div class="col-md-2">
                    <a href="/mailinglistsubscribers/create" class="btn btn-primary btn-flat">Add New Mailing List Subscriber</a>
                </div>

            </div>
        </div>    

    </div>


    <div class='table-responsive'>
        <table class="table table-hover table-bordered pull-left table-striped table-condensed admin-user-table">
            <thead>
                <tr>
                    <th>
                        <!-- <button id="checkall" class="btn-info">Toggle</button>-->
                        <input type="checkbox" id="checkall" class="check" value="" />
                    </th>
                    <th>Mailing List Subscriber</th>
                    <th>Mailing List Subscriber Email</th>
                    <th>Mailing List</th>
                    <th>&nbsp;</th>



                </tr>
            </thead>
            <tbody>
                @foreach($mailinglist_subscriber as $c)
                <tr>
                    <td  data-title="Select">
                        <input type="checkbox" class="check" name="cid[]" value="{{$c->mailinglist_subscriber_id}}" id="cid{{$c->mailinglist_subscriber_id}}" />
                    </td>

                    <td  data-title="Question">
                        <a href="/mailinglistsubscribers/{{$c->mailinglist_subscriber_id}}/edit/" title="">
                            {{$c->mailinglist_subscriber_name}} 
                        </a>
                    </td>
                    <td>
                        {{$c->mailinglist_subscriber_email}} 
                    </td>
                    <td>
                        {{$c->mailinglist->mailinglist_name}} 
                    </td>
                    <td> 
                        <a href="/mailinglistsubscribers/{{$c->mailinglist_subscriber_id}}/edit/" title="Edit">
                            <i class="fa fa-pencil-square fa-lg"></i>&nbsp;Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>


        </table>
    </div>


</form>

{{$mailinglist_subscriber->appends(Input::except('page'))->links();}}

@stop

