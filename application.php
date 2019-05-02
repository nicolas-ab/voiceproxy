<?php
require_once("api_functions.php");
//Retrieve the conversation ID from the JSON object sent to eventURL

//$headers = array_merge(getallheaders(), $_SERVER); //var_dump($headers);

$method = $_SERVER['REQUEST_METHOD'];

$request = array_merge($_GET, $_POST);
$app_id=NULL;
$api_key=NULL;
$api_secret=NULL;
$country=NULL;
$msisdn=NULL;
//print_r($request);
// at first, we won't look for any parameters but the api credentials. When it works, find a way to transform a part of the URI into an argument... 

//print_r($_GET);

if(!(isset( $_GET['api_key']) && isset( $_GET['api_secret'])))
{   
    // credentials must always here be present. 
    echo 'error: you need to pass arguments api_key and api_secret';
} else {
    $api_key=$_GET['api_key'];
    $api_secret=$_GET['api_secret'];
    if( isset($_GET['app_id']))
    {
        // we have an app id, we can be looking for a specific app (getting info or configuring/unconfiguring a number...)
        $app_id=$_GET['app_id'];
    }
    if (! isset($_GET['msisdn'])) {
    // there is no number, so we are looking to get info on a specific app:
        $result= get_my_apps($api_key,$api_secret,$app_id);
    } else {
        // credential, AP ID, MSISDN are here: this is a configuration or unconfiguration of number on an application
        //first, let's check we have all we need besides MSISDN and app_id (country, msisdn)

        if (!isset($_GET['country']) or !isset($_GET['action']))
        {
            echo 'error: you need to pass arguments country and action';
        }else {
                // we have all we need
                $country=$_GET['country'];
                $msisdn=$_GET['msisdn'];
                switch($_GET['action']){
                    case 'configure':
                        $result=update_number($api_key,$api_secret,$app_id,$country,$msisdn);
                        break;
                    case 'unconfigure':
                        // looks like we don't need the app_id when unconfiguring a number, so test to be updated
                        $result=update_number($api_key,$api_secret,NULL,$country,$msisdn);
                        break;
                }
            }
        }
        
   echo json_encode($result);
}

?>


