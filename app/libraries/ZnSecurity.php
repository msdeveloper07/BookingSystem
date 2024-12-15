<?php

Class ZnSecurity
{
    public static function Authorize()
    {
        
         if(!Auth::check())
                {
                     return Redirect::to('/admin/login')->with('error_message',"You are either not logged in or you dont have permissions to access this page");
                }
                
         if(Auth::user()->reset_password_code!='')
              {
                  return Redirect::to('/admin/changePassword')->with('error_message',"Please create your password");

              }

         $user = User::find(Auth::user()->id);
         if($user->user_status=='deactive')
              {
                  Auth::logout();
                  return Redirect::to('/admin/login')->with('error_message',"Your Account is Disabled. Contact Support for more information.");
              }
    }
   
    public static function AuthorizeSite()
    {
        
         if(!Auth::check())
                {
                     return Redirect::to('login')->with('error_message',"You are either not logged in or you dont have permissions to access this page");
                }
                
         if(Auth::user()->reset_password_code!='')
              {
                  return Redirect::to('changePassword')->with('error_message',"Please create your password");

              }

         $user = User::find(Auth::user()->id);
         if($user->user_status=='deactive')
              {
                  Auth::logout();
                  return Redirect::to('/login')->with('error_message',"Your Account is Disabled. Contact Support for more information.");
              }
             
    }
    
    public static function checkPermission($user_group_id,$element,$title, $component = 'backend')
    {
            $permission = DB::table('user_group_permissions as up')
                            ->leftJoin('permissions as p','p.permission_id','=','up.permission_id')
                            ->where('up.user_group_id',$user_group_id)
                            ->where('p.component',$component)
                            ->where('p.element',$element)
                            ->where('p.title',$title)
                            ->count();
            
            
            
            if($permission>0)
              return TRUE;
            else
              return FALSE;
            
    }
}