<?php

return array(
    'currencies' => array(
		'USD' => '$',
                'GBP' => '&pound;',
                'EUR' => '&euro;',
                'INR' => '&#x20b9;',
	),
    'salutations' => array(
		'Mr.' => 'Mr.',
                'Mrs.' => 'Mrs.',
                'Ms.' => 'Ms.',
                'Dr.' => 'Dr.',
                
	),
    
    'payment_methods' => array(
		'Paypal' => 'Paypal',
                'Upwork' => 'Upwork',
                'ELance' => 'Elance',
                'PPH' => 'PPH',
                'Direct' => 'Direct',
	),
     'nearest_airport' => array(
		'US' => 'USA',
		'UK' => 'United Kindom',
		'IN' => 'India',
		'AU' => 'Australia',
	),
     'task_priorities' => array(
		'10' => '10 [Immediate Attention Required]', 
		'8' => '8 [Should Be Dealt Today]', //Color code: Orange
		'5' => '5 [For Sure By Tomorrow]', // Color code: Yellow
		'3' => '3 [Should Be Done This Week]', //color code : Green
		'1' => '1 [Can Wait]', // Color code: 
	),
     'task_priorities_color_code' => array(
		'10' => 'bg-red', 
		'8' => 'bg-orange', //Color code: Orange
		'5' => 'bg-green', // Color code: Yellow
		'3' => 'bg-light-blue', //color code : Green
		'1' => 'bg-lime', // Color code: 
	),
     'follow_type' => array(
		'all_activities' => 'All Activities', 
		'all_tasks_assigned_by_me' => 'All Tasks Assigned By Me', //Color code: Orange
		'only_daily_update' => 'Only Daily Updates', // Color code: Yellow
		'only_weekly_updates' => 'Only Weekly Updates', //color code : Green
		'only_client_communication' => 'Only Client Communication', // Color code: 
	),
   
     'project_types' => array(
		'hourly' => 'Hourly', 
		'fixed' => 'Fixed' 
	),
   
     'billing_cycles' => array(
		'weekly' => 'Weekly [Friday]', 
		'fortnightly' => 'Fortnightly [Friday]',
                'monthly'=>'Monthly [Last Working Day]',
                'particular_date'=>'Specific Date',
                'particular_day'=>'Specific Week Day',
	),
   
   
     'timezones' => array(
		'-5:30' => '-5:30', 
		'-5:00' => '-5:00', 
		'-4:30' => '-4:30', 
		'-4:00' => '-4:00', 
		'-3:30' => '-3:30', 
		
	),
   
);
