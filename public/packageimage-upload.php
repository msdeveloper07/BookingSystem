<?php

 // Define a destination
 $targetFolder = 'packageimage/'; // Relative to the root


if (!empty($_FILES)) 
    {
        $raw_name = str_replace(" ","-", $_FILES['file']['name']);
        $target_file_name=random_string().'-'. str_replace(" ","-", $_FILES['file']['name']);

    
	$tempFile = $_FILES['file']['tmp_name'];
	$targetPath = dirname(__FILE__) . '/' . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $target_file_name  ;
	
        $fileTypes = array('jpg','jpeg','gif','png');
        $fileParts = pathinfo($_FILES['file']['name']);
       

	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
                
	        $files=array();
                $files['base_path'] = dirname(__FILE__) . '/' . $targetFolder;
                $files['file_name'] = $target_file_name;
                $files['file_path'] = $targetFile;
                $files['file_id'] = random_string();
                $files['raw_name'] = $raw_name;
                $files['logo_image'] = "/packageimage/$target_file_name";
  
               
                echo json_encode($files);
	} else {
		echo 'Invalid file type.';
	}
        
       
}

function random_string($type="string",$random_string_length='15')
{
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $numbers = '0123456789';
    $sentance = 'abcdefghijklmnopqrstuvwxyz0123456789 .,-';  
    
    
    $string = '';
        for ($i = 0; $i < $random_string_length; $i++) {
            if(($type=="string")||($type=="email"))
                 $string .= $characters[rand(0, strlen($characters) - 1)];
            elseif($type=="sentance")
                 $string .= $sentance[rand(0, strlen($sentance) - 1)];
            elseif($type=="numbers")
                 $string .= $numbers[rand(0, strlen($numbers) - 1)];

        }
        
     if($type=="email")
     {
         $string .= "@gmail.com";
     }
     return $string;
}


?>