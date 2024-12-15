<?php

Class Task extends Eloquent
{
    protected $primaryKey = 'task_id';
    protected $table = 'tasks';
    public $timestamps = false;
    
    public function user_name()
    {
        return $this->hasOne('User','id','assign_to');
    }
    
    
     public function taskComment()
    {
        return $this->hasOne('TaskComment','task_id','task_id');
    }
}
