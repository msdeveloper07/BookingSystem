<?php

Class TaskComment extends Eloquent
{
    protected $primaryKey = 'comment_id';
    protected $table = 'task_comments';
    public $timestamps = false;
    
  public function username()
  {
      return $this->hasOne('User','id','user_id');
  }
  
  
  
}
