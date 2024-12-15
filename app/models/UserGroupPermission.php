<?php

Class UsergroupPermission extends Eloquent
{
    protected $primaryKey = 'user_group_permissions_id';
    protected $table = 'user_group_permissions';
    public $timestamps = false;
    
   public function permission(){
       return $this->hasMany('Permission','permission_id','permission_id');
       
       
   }
}
