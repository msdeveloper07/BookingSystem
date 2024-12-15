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
        <form  role="form" action='/emailTemplates' name='email_template_form' id='email_template_form' method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Title</label>
                                <input type="text" placeholder="Possible subject for the Email" id="email_template_title" name="email_template_title" class="form-control required" value="{{Input::old('email_template');}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputTitle">Content</label>
                                <textarea class="textarea" id="content" name="content" placeholder="Email Template Text" 
                                          style="width: 100%; height: 325px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>                                
                            </div>
                        </div>
                    </div>

                    <div id="upload-doc-div" class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label for="InputStatus">Upload Documents: </label>
                                <noscript><div class="alert alert-danger">Javascript Must be Enabled for File Upload.</div></noscript>
                                <input type="file" class="btn btn-flat btn-upload" id="upload-button" name="file" />
                                <input type="hidden" value="" name="specs_location">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <div id="progress_bar"></div>
                            </div>
                        </div>   
                    </div>

                    <div class="row">
                        <div class="alert alert-info" id="file_limit" style="display: none">
                           You can upload only <strong>1 file</strong> so if you have more files to attach please add them to a zip file.
                        </div>
                    </div>

                    <div class="row" style="display:block" id="file_holder">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <div id="prev_upload">

                                    <table class="table table-striped table-bordered table-hover table-condensed" style="display:none;">
                                        <thead>
                                            <tr>
                                                <th style="width:15%;">File Type</th>
                                                <th>Filename</th>
                                                <th style="width:20%;">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                </div>
                            </div>
                        </div> 
                    </div>






                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </form>

    </div>

    <div class="col-md-6">
        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Example</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <p class="text-muted"> 
                    Dear {name},<br/>
                    <br/>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eget tempor nulla. Pellentesque auctor erat nec justo suscipit ultrices. Quisque commodo, odio eget varius imperdiet, eros ante molestie lacus, molestie viverra neque nisi at elit. Morbi nec congue urna.<br />
                    <br />    
                    {website_link}<br />
                    <br />
                    Regards<br />
                    {sender_name}
                </p>

            </div><!-- /.box-body -->

        </div>


        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Available Variables</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Variable</th>
                            <th>Purpose</th>
                        </tr>
                        <tr>
                            <td>{name}</td>
                            <td>Name of Contact of the Lead</td>
                        </tr>
                        <tr>
                            <td>{lead_title}</td>
                            <td>Title of Lead</td>
                        </tr>
                        <tr>
                            <td>{sender_name}</td>
                            <td>Name of the Sender</td>
                        </tr>
                        <tr>
                            <td>{website_link}</td>
                            <td>Link to the Booking Gatesway website</td>
                        </tr>
                        <tr>
                            <td>{follow_up_on}</td>
                            <td>Date on which the follow up is scheduled</td>
                        </tr>
                        <tr>
                            <td>{follow_up_time}</td>
                            <td>Time on which the follow up is scheduled</td>
                        </tr>
                        <tr>
                            <td>{interest}</td>
                            <td>Interest of the lead</td>
                        </tr>
                    </table>
                </div>
            </div><!-- /.box-body -->

        </div>
    </div>

</div>

@stop