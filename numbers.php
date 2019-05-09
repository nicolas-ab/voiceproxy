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

if(!(isset( $request['api_key']) && isset( $request['api_secret'])))
{
    echo 'error: you need to pass arguments api_key and api_secret';
} else {
    $api_key=$request['api_key'];
    $api_secret=$request['api_secret'];
    if( isset($request['country']))
    {
        $country=$request['country'];
    }
    if( isset($request['type']))
    {
        $type=$request['type'];
    }
    if( isset($request['features']))
    {
        $features=$request['features'];
    }
   $result= In_my_numbers_is_there_a_good_one_available($api_key,$api_secret,$country,$type,$features);
   echo $result;
}


?>
