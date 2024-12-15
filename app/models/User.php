<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
       

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
                
        public static function checkPermission($user_group_id,$element,$title)
        {
            $permission = DB::table('user_group_permissions as up')
                            ->leftJoin('permissions as p','p.permission_id','=','up.permission_id')
                            ->where('up.user_group_id',$user_group_id)
                            ->where('p.element',$element)
                            ->where('p.title',$title)
                            ->count();
            
            if($permission>0)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
            
        }
        
        public function userGroup()
        {
            return $this->belongsTo('Usergroup','user_group_id');
        }

        public function lead_creator()
        {
            return $this->hasOne('Leads','created_by');
        }
        
        public function lead_updator()
        {
            return $this->hasOne('Leads','updated_by');
        }
        
        public function note()
        {
            return $this->hasMany('Note','user_id');
        }
        
           public function images()
        {
            return $this->hasOne('Upload','id','id');
        }

}
