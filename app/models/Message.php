<?php

Class Message extends Eloquent
{
    protected $primaryKey = 'message_id';
    protected $table = 'messages';
    public $timestamps = false;
   
    public function leads()
    {
        return $this->belongsTo('Lead','lead_id');
    }
   
    public function attachments()
    {
        return $this->hasMany('Attachment','message_id');
    }
   
    public static function totalUnread()
    {
        $total_unread_count = Message::where('message_status','unread')
                                    ->where('inbox','1')
                                    ->where('parent_id','0')
                                    ->get()
                                    ->count();
        return $total_unread_count;
    }
    
    public static function totalUnreadInFolder($folder_id)
    {
        $total_unread_count = Message::where('message_status','unread')
                                    ->where('inbox','1')
                                    ->where('parent_id','0')
                                    ->where('folder_id',$folder_id)
                                    ->get()
                                    ->count();
        return $total_unread_count;
    }
    
    public static function totalUnactionedInFolder($folder_id)
    {
        $total_unread_count = Message::where('action_taken','=','0')
                                    ->where('parent_id','0')
                                    ->where('folder_id',$folder_id)
                                    ->get()
                                    ->count();
        return $total_unread_count;
    }
}
