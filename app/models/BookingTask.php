<?php

Class BookingTask extends Eloquent
{
    protected $primaryKey = 'booking_task_id';
    protected $table = 'booking_tasks';
    public $timestamps = false;
    
    public function user_name()
    {
        return $this->hasOne('User','id','assign_to');
    }
    
    
     public function taskComment()
    {
        return $this->hasOne('TaskComment','booking_task_id','booking_task_id');
    }
}
