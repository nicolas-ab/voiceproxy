<?php
require_once("_constants.php");
function list_owned_number($api_key,$api_secret,$index, $size)
{
    $base_url= "https://rest.nexmo.com/account/numbers?".http_build_query([
            'api_key' =>  $api_key,
            'api_secret' => $api_secret,
            'index' => $index,
            'size' => $size
    ]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $base_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, 0);
    return curl_exec($ch);
}

function search_available_number($country_code, $number_type)
{
    echo $number_type;
    $base_url = "https://rest.nexmo.com/number/search?".http_build_query([
        'api_key' =>  NEXMO_API_KEY,
        'api_secret' => NEXMO_API_SECRET,
        'country' => $country_code,
        'type' => $number_type,
        'features' => 'VOICE'
    ]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $base_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, 0);
    echo '<pre>'.$base_url.'<pre>';
    return curl_exec($ch);
}

function create_application($api_key,$api_secret,$name,$event_url,$answer_url)
{
    $base_url = 'https://api.nexmo.com' ;
    $version = '/v1';
    $action = '/applications/?';

//Create an Application for Voice API.
    $url = $base_url . $version . $action . http_build_query([
    'api_key' =>  $api_key,
    'api_secret' => $api_secret,
    'name' => $name,
    'type' => 'voice',
    'answer_url' => $answer_url,
    'event_url' => $event_url
]);
//In this example, answer_url points to a static NCCO that creates a Conference
//

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json", "Content-Length: 0" ));
curl_setopt($ch, CURLOPT_HEADER, 1);
$response = curl_exec($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);

if (strpos($header, '201')){
    $application = json_decode($body, true);
    if (! isset ($application['type'])){
        $result['app_id']=$application['id'];
        $result['public_key']=$application['keys']['public_key'];
        $result['private_key']=$application['keys']['private_key'];
    }else {
        echo ( "Error: " . $application['type']
            . " because of " . $application['error_title'] . "\n" );
    }
} else {
    $error = json_decode($body, true);
    echo("Your request failed because:\n");
    echo("  " . $error['type'] . "  " . $error['error_title']   );
}
    return json_encode($result);
}

function update_number($api_key,$api_secret,$app_id,$country,$msisdn)
{
  
    $base_url = "https://rest.nexmo.com/number/update?".http_build_query([
        'api_key' =>  $api_key,
        'api_secret' => $api_secret,
        'country' => $country,
        'msisdn' => $msisdn,
        'voiceCallbackType' => 'app',
        'voiceCallbackValue' => $app_id
    ]);
    $header[] = 'Content-type: application/x-www-form-urlencoded';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $base_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    return curl_exec($ch);
}

function get_my_apps($api_key,$api_secret,$app_id)
{
    //echo 'mon app_id:'.$app_id;
   if(is_null($app_id))
   {
    $base_url= "https://api.nexmo.com/v2/applications?".http_build_query([
        'page_size' =>  100,
        'page' => 1
        ]);
   } else {
    $base_url= "https://api.nexmo.com/v2/applications/$app_id";
   }


    $header = array();
    
    $header[] = 'Content-type: application/json';
    $header[] = 'Authorization: Basic '.base64_encode($api_key.':'.$api_secret);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $base_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 0);
    return curl_exec($ch);
}

// this function configure a LVN on a Voice Application
function configure_proxy($country, $msisdn, $app)
{
    $base_url= "https://rest.nexmo.com/account/numbers/update?".http_build_query([
        'api_key' =>  NEXMO_API_KEY,
        'api_secret' => NEXMO_API_SECRET,
        'country' => $country,
        'msisdn' => $msisdn,
        'voiceCallbackValue' => $app,
    ]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $base_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, 0);
    return curl_exec($ch);
}




function In_my_numbers_is_there_a_good_one_available($api_key,$api_secret,$country_code, $number_type, $features)
{
    $index =1;
    $size =100;
    $my_numbers = json_decode(list_owned_number($api_key,$api_secret,1,1));
    $nb_result = $my_numbers->count;

    for ($index=1; (($index-1)*$size) < $nb_result ;$index=$index+1)
    {

        sleep(1);// without a timer, the second call of list_owned_number return an empty result in my environment
        $my_numbers = json_decode(list_owned_number($api_key,$api_secret,$index,$size));
        $my_good_numbers = array();
        //print_r($my_numbers);
        foreach ($my_numbers->numbers as $number )
        {
            // is this number from the right country ?
            if(is_null($country_code) or ($number->country == $country_code))
            {
                if(is_null($number_type) or ($number->type == $number_type))
                {
                    if(is_null($features))
                    {
                       // no feature specified
                        $my_good_numbers[] = $number;
                    } elseif ($features === 'VOICE' or $features === 'SMS' ){
                        // filter on one feature
                        foreach ($number->features as $features_number)
                        {
                            if($features_number === $features )
                            {
                                    $my_good_numbers[] = $number;
                            }
                        }
                    } elseif ($features == 'SMS,VOICE'){
                        // filter with both feature
                        if ($number->features == ["VOICE","SMS"]){
                            $my_good_numbers[] = $number;
                        }
                    }
                }
            }
        }
    }
    return json_encode($my_good_numbers);

}
