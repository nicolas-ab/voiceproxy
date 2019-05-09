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
$action=NULL;
$name=NULL;
$event_url=NULL;
$answer_url=NULL;


// at first, we won't look for any parameters but the api credentials. When it works, find a way to transform a part of the URI into an argument... 

// todo: the logics belows deserve to be refactored (could be simplier)
if(!(isset( $request['api_key']) && isset( $request['api_secret'])))
{   
    // credentials must always here be present. 
    echo 'error: you need to pass arguments api_key and api_secret';
} else {
    $api_key=$request['api_key'];
    $api_secret=$request['api_secret'];
    if( isset($request['app_id']))
    {
        // we have an app id, we can be looking for a specific app (getting info or configuring/unconfiguring a number...)
        $app_id=$request['app_id'];
    }
    if( isset($request['action']))
    {
        // we have an app id, we can be looking for a specific app (getting info or configuring/unconfiguring a number...)
        $action=$request['action'];
    }
    if ($action=='create')
    {// here, we will create a voice application
        if (!isset($request['name']) or !isset($request['event_url']) or !isset($request['answer_url']))
        {
            echo 'error: you need to pass arguments name , event_url and answer_url';

        }else {
            $name=$request['name'];
            $event_url=$request['event_url'];
            $answer_url=$request['answer_url'];
            $result=create_application($api_key,$api_secret,$name,$event_url,$answer_url);
            echo $result;
        }

        exit(0);
    }
    if (! isset($request['msisdn'])) {
    // there is no number, so we are looking to get info on a specific app:
        $result= get_my_apps($api_key,$api_secret,$app_id);
    } else {
        // credential, AP ID, MSISDN are here: this is a configuration or unconfiguration of number on an application
        //first, let's check we have all we need besides MSISDN and app_id (country, msisdn)

        if (!isset($request['country']) or !isset($request['action']))
        {
            echo 'error: you need to pass arguments country and action';
        }else {
                // we have all we need
                $country=$request['country'];
                $msisdn=$request['msisdn'];
                switch($request['action']){
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
        
   echo $result;
}

?>


