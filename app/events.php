<?php

Event::listen('history.add', function($history_array)
{
    $history = new History();
    $history->submitted_on = isset($history_array['datetime'])&&($history_array['datetime']!='')?$history_array['datetime']:date('Y-m-d H:i:s');
    $history->user_id = isset($history_array['user_id'])&&($history_array['user_id']!='')?$history_array['user_id']:Auth::user()->id;
    $history->history_title = $history_array['title'];
    $history->history_content = $history_array['content']!=''?$history_array['content']:$history_array['title'];
  
    //$history->file_id = $history_array['id'];
    $history->save();
    
});

?>