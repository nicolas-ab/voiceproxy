<?php
require_once("api_functions.php");
//Retrieve the conversation ID from the JSON object sent to eventURL

$method = $_SERVER['REQUEST_METHOD'];

$request = array_merge($_GET, $_POST);
$type=NULL;
$country=NULL;
$features=NULL;
$api_key=NULL;
$api_secret=NULL;
//print_r($request);

if(!(isset( $_GET['api_key']) && isset( $_GET['api_secret'])))
{
    echo 'error: you need to pass arguments api_key and api_secret';
} else {
    $api_key=$_GET['api_key'];
    $api_secret=$_GET['api_secret'];
    if( isset($_GET['country']))
    {
        $country=$_GET['country'];
    }
    if( isset($_GET['type']))
    {
        $type=$_GET['type'];
    }
    if( isset($_GET['features']))
    {
        $features=$_GET['features'];
    }
   $result= In_my_numbers_is_there_a_good_one_available($api_key,$api_secret,$country,$type,$features);
   echo $result;
}

/*
$buffer=file_get_contents('php://input');
$parameters = serialize($request);


$fp = fopen("request.txt", 'a');
fwrite($fp,$buffer);
fclose($fp);
*/
?>
