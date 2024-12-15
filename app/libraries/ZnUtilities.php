<?php
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
//require('/html2text/src/Html2Text.php');

class ZnUtilities {

    public static function doMessage() {
        return "Hello Message";
    }

    public static function loadJsFiles() {
        
    }

    // Random Number string
    public static function random_string($type = "string", $random_string_length = '8') {
        $alphabets = 'abcdefghijklmnopqrstuvwxyz';
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $numbers = '0123456789';
        $sentance = 'abcdefghijklmnopqrstuvwxyz0123456789 .,-';


        $string = '';
        for ($i = 0; $i < $random_string_length; $i++) {

            if (($type == "string") || ($type == "alphabets"))
                $string .= $alphabets[rand(0, strlen($alphabets) - 1)];
            elseif ($type == "alphanumeric")
                $string .= $characters[rand(0, strlen($characters) - 1)];
            elseif ($type == "email")
                $string .= $characters[rand(0, strlen($characters) - 1)];
            elseif ($type == "sentance")
                $string .= $sentance[rand(0, strlen($sentance) - 1)];
            elseif ($type == "numbers")
                $string .= $numbers[rand(0, strlen($numbers) - 1)];
        }

        if ($type == "email") {
            $string .= "@gmail.com";
        }
        return $string;
    }

    //Print Array
    public static function pa($array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

//LOAD JS FILES TO THE WEBPAGE
    public static function push_js_files($file) {
        if ($file != '') {
           // $_SESSION['JS'][] = $file;
            Session::push('JS', $file);
        }
    }

    public static function push_js($js) {
        if ($js != '') {
           // $_SESSION['JS_script'][] = $js;
            Session::push('JS_script', $js);
        }
    }

    public static function load_js() {
        if (Session::has('JS_script'))
        {
            foreach (Session::get('JS_script') as $js) {
                echo '<script type="text/javascript">' . $js . '</script>' . "\n";
            }
            
            Session::forget('JS_script');
        }
        
        
        /*if (isset($_SESSION['JS_script']) && count($_SESSION['JS_script']) > 0) {
            foreach ($_SESSION['JS_script'] as $js) {
                echo '<script type="text/javascript">' . $js . '</script>' . "\n";
            }
        }
        unset($_SESSION['JS_script']);*/
    }

    public static function load_js_files() {
        
         if (Session::has('JS'))
        {
            foreach (Session::get('JS') as $js) {
              if (strpos($js, 'http') === FALSE) {
                    echo '<script src="' . url() . "/assets/js/" . $js . '" type="text/javascript"></script>' . "\n";
                } else {
                    echo '<script src="' . $js . '" type="text/javascript"></script>' . "\n";
                }
            }
            
            Session::forget('JS');
        }
        
       /* if (isset($_SESSION['JS']) && count($_SESSION['JS']) > 0) {
            foreach ($_SESSION['JS'] as $js) {

                if (strpos($js, 'http') === FALSE) {
                    echo '<script src="' . base_url() . "js/" . $js . '" type="text/javascript"></script>' . "\n";
                } else {
                    echo '<script src="' . $js . '" type="text/javascript"></script>' . "\n";
                }
            }
        }
        unset($_SESSION['JS']);*/
    }

//LOAD CSS FILES TO THE WEBPAGE
    public static function push_css_files($file) {
        if ($file != '') {
            //$_SESSION['CSS'][] = $file; 
            Session::push('CSS', $file);
        }
        
    }

    public static function load_css_files() {
        /*if ((isset($_SESSION['CSS'])) && count($_SESSION['CSS']) > 0) {
            foreach ($_SESSION['CSS'] as $css) {
                echo '<link href="' . base_url() . "css/" . $css . '" type="text/stylesheet" rel="stylesheet" />' . "\n";
            }
        }
        unset($_SESSION['CSS']);
        */
        
         if (Session::has('CSS'))
        {
            foreach (Session::get('CSS') as $css) {
              if (strpos($css, 'http') === FALSE) {
                    echo '<link href="' . url() . "/assets/css/" . $css . '" rel="stylesheet" type="text/stylesheet" />' . "\n";
                } else {
                    echo '<link href="' . $css . '" rel="stylesheet" type="text/css" />' . "\n";
                }
            }
            
            Session::forget('CSS');
        }
        
        
    }
    
    public static function format_date($date,$type=null)
{
    switch($type)
    {
        case 1:
        default:
            {
                $datetime = new DateTime($date);
                $new_date = $datetime->format('Y-m-d');// return a date in format like JAN 01 2009
                break;
            }
        case 2:
            {
                $datetime = new DateTime($date);
                $new_date = $datetime->format('M d, Y');// return a date in format like JAN 01 2009
                break;
            }
        case 3:
            {
                $datetime = new DateTime($date);
                $new_date = $datetime->format('M d, Y H:i A ');// return a date in format like 14:45 JAN 01
                break;
            }
        case 4:
            {
                $datetime = new DateTime($date);
                $new_date = $datetime->format('H:i A ');// return a date in format like 14:45 JAN 01
                break;
            }

    }

    return $new_date;
}

public static function create_breadcrumbs($nav_array)
{
    $nav ='';
    $nav .='<ul class="breadcrumb">';
    $nav .='<li><a href="/"><i class="fa fa-dashboard"></i>Dashboard</a></li>';
    
    foreach($nav_array as $key=>$value)
    {
        if($value['active']=='1')
        {
            $class = 'class="active"';
            $nav .='<li '.$class.'>'.$key.'</li>';
        }
        else
        {
            $class = "";
            $nav .='<li '.$class.'><a href="'.$value['link'].'">'.$key.'</a></li>';
        }
        
        
    }
    $nav .='</ul>';
        
    echo $nav;
}

public static function create_submenus($nav_array)
{
    $nav ='<div class="sub-links">';
    $nav .='<ul class="page-sublinks">';
    foreach($nav_array as $key=>$value)
    {
        if($value['active']=='1')
        {
            $class = 'class="active"';
            $nav .='<li '.$class.'><a href="'.$value['link'].'"><i class="fa '.$value['icon'].'"></i>'.$key.'</a></li>';
        }
        else
        {
            $class = "";
            $nav .='<li '.$class.'><a href="'.$value['link'].'"><i class="fa '.$value['icon'].'"></i>'.$key.'</a></li>';
        }
        
        
    }
    $nav .='</ul>';
        
    echo $nav;
}



public static function lastQuery()
{
    $queries = DB::getQueryLog();
    $last_query = end($queries);
            
    $bindings = array();
    
    foreach($last_query['bindings'] as $b)
    {
        $bindings[] = "'".$b."'";
    }
    
    return str_replace_array('\?',$bindings, $last_query['query']);
    //echo str_replace_array('\?', "'".$last_query['bindings']."'", $last_query['query']);
}

public static function echoViewContent($viewName, $data)
{
    $view = View::make($viewName);
    
    foreach($data as $k=>$d)
    {
        $view->$k = $d;
    }
    
    echo $view->render();
}

public static function basicPageVariables($component = "Dashboard", $page_title="", $active_nav="", $navigation = "1")
{
    $data = array();
    
    $data['component'] = $component;
    $data['page_title'] = $page_title;
    $data['active_nav'] = $active_nav;
    $data['navigation'] = $navigation;
    $data['currency'] = CurrencyConverter::all();
    
    return $data;
}

public static function array_to_csv($array, $download = "")
	{
		if ($download != "")
		{	
			header('Content-Type: application/csv');
			header('Content-Disposition: attachement; filename="' . $download . '"');
		}		

		ob_start();
		$f = fopen('php://output', 'w') or show_error("Can't open php://output");
		$n = 0;		
		foreach ($array as $line)
		{
			$n++;
			if ( ! fputcsv($f, $line))
			{
				show_error("Can't write line $n: $line");
			}
		}
		fclose($f) or show_error("Can't close php://output");
		$str = ob_get_contents();
		ob_end_clean();

		if ($download == "")
		{
			return $str;	
		}
		else
		{	
			echo $str;
		}		
	}

        public static function replaceDashes (&$obj) {
            $vars = get_object_vars($obj);
            foreach ($vars as $key => $val) {
                if (strpos($key, "-") !== false) {
                    $newKey = str_replace("-", "_", $key);
                    $obj->{$newKey} = $val;
                    unset($obj->{$key});
                }
            }
    }
    
    public static function getMysqlInStatement($data_array)
    {
        if(is_array($data_array))
        {
            $data ='';
            $string = " (";

            foreach($data_array as $d)
            {
               $data .="'".$d."',";  
            }
            $string .=trim($data,',');
            $string .= " )";


            return $string;
        }
    }
    
public static function scrapHtml($url, $options){
   

    $client = new Client();
    $crawler = $client->request('GET', $url);
    

     
       $data_array = array();
       $final_array = array();
       
       //ZnUtilities::pa($options);
        
         foreach($options as $key=>$attr){
                $changes_array[$key][$attr] =  $crawler->filter($key)->each(function ($node) use ($data_array, $attr) {
                   $attribute_array = explode(',',$attr); 
                     foreach($attribute_array as $a){
                        $data_array = $node->attr($a);    
                     }
               return $data_array;
           });
           
           $final_array = array_merge($final_array, $changes_array);
           
          }
          return $final_array;
        
   }
 }
        