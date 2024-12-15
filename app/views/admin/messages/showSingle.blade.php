@extends('layouts.emptyTemplate')

@section('content')

 <?php 
// echo mb_detect_encoding($message->content);
// die;
 
//     echo  $content = iconv( 'ASCII','ISO-8859-1//IGNORE', $message->content); 
     echo  $content = iconv( 'UTF-8','UTF-8//IGNORE', $message->content); 
     //echo  $content = iconv( 'ISO-8859-1', 'UTF-8//IGNORE', $message->content); 
 ?>

@stop