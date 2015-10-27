<?php

function csv_to_array($filename='', $delimiter=',')
{
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;
	
	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
		{
			if(!$header)
				$header = $row;
			else
				$data[] = array_combine($header, $row);
		}
		fclose($handle);
	}
	return $data;
}
$suburb_file = dirname(__FILE__).'/../languages/postal_codes/GP_codes.csv';
$postal_codes = csv_to_array($suburb_file,';');
$city_options = array();
foreach($postal_codes as $row)
{

    $city_options[str_replace("'"," ",ucwords(strtolower(trim($row['Group Name']))))]['name'] = str_replace("'"," ",ucwords(strtolower(trim($row['Group Name']))));
    $city_options[str_replace("'"," ",ucwords(strtolower(trim($row['Group Name']))))]['suburbs'][] = array('name'=> str_replace("'"," ",ucwords(strtolower(trim($row['Location Code Name'])))),'code'=>$row['Post Code']);
    // exit(var_dump($city_options));
//                 if(strstr(strtolower($row['group']), 'pretoria') || strstr(strtolower($row['group']), '(gp)')|| strstr(strtolower($row['group']), 'gauteng')|| strstr(strtolower($row['suburb']), 'gauteng'))
//                 {
//                     //$gauteng_postals[] =  $row;
//                     
//                     $city_options[ucwords(strtolower($row['group']))]['name'] = ucwords(strtolower($row['group']));
//                     $city_options[ucwords(strtolower($row['group']))]['suburbs'][] = array('name'=> ucwords(strtolower($row['suburb'])),'code'=>$row['post_code']);
//                     //$gauteng_postals_options[$row['post_code']] = ucwords(strtolower($row['suburb'])." (".$row['post_code'].")");
//                 }
//                 else
//                {
//                     continue;
//                }
}
echo json_encode($city_options[$_GET['group']]);