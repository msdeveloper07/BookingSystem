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
    <form>
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">General</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                    <div class="col-md-4">
                         <label for="InputTitle">Full Name</label>
                         <input type="text" placeholder="Full name" id="name" name="name" class="form-control required" readonly="" value="{{$user->name}}">
                        
                        </div>
                 
                         
                            <div class="col-md-6">
                            <div class="form-group">
                            <label for="InputTitle">User Profile Image</label>
                            </br>
                            
                            <?php  $preet = Upload::where('id',Auth::user()->id)->pluck('file_name'); ?>
                            <img src="<?php echo url().'/upload/'.$preet; ?>" height="150px" width="150px" class="">
                        </div>
                    </div>
                
                    </div>
                   
                    <div class="row">
                       <div class="col-md-6">
                           <div class="form-group">
                             <label for="InputTitle">Email</label>
                             <input type="email" placeholder="Ex: you@abc.com" id="email" readonly name="email" class="form-control required"  value="{{$user->email}}">
                            
                              </div>
                       </div>
                   </div>
                    
                      <div class="row">
                       <div class="col-md-6">
                           <div class="form-group">
                              <label for="InputTitle">User Group</label>
                               <select name="user_group_id" id="user_group_id" readonly class="form-control required">
                              <?php $user_group = Usergroup::where('user_group_id',$user->user_group_id)->pluck('user_group_name');
                              ?>
                            <option value="{{$user_group}}">{{$user_group}}</option>
                               </select>
                              </div>
                       </div>
                   </div>
                      <div class="row">
                       <div class="col-md-6">
                           <div class="form-group">
                           
                              
                            
                            <label for="InputEmail">User Status</label>
                                <label class="radio-inline">
                                    <input readonly  type="radio" name="user_status" value="active"   id="user_status_active"  checked="checked">{{$user->user_status}}
                                </label>
                              
                            </div>
                       </div>
                   </div>
                    
                </div><!-- /.box-body -->
                
                  <div class="box-footer">
                      <a href="/users/{{$user->id}}/edit" <button class="btn btn-primary" type="submit">Edit Profile</button></a>
                      <a href='/image/create' class='btn btn-success'>Upload Image</a>
                </div>
                
            </div>
            
        </div>

             
      
    </form>
</div>

@stop