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


function buy_number()
{
    return 0;
}

function create_application()
{
    return 0;
}

function update_number($api_key,$api_secret,$app_id,$country,$msisdn)
{

        echo '- api_key:'.$api_key;
        echo '- api_secret:'.$api_secret;
        echo '- app_id:'.$app_id;
        echo '- country:'.$country;
        echo '- msisdn:'.$msisdn;
        
            $base_url = "https://rest.nexmo.com/number/update?".http_build_query([
                'api_key' =>  $api_key,
                'api_secret' => $api_secret,
                'country' => $country,
                'msisdn' => $msisdn,
                'voiceCallbackType' => 'app',
                'voiceCallbackValue' => $app_id,
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

// this function create a proxy Voice Application
function create_proxy()
{
    return $application_ID;
}



function In_my_numbers_is_there_a_good_one_available($api_key,$api_secret,$country_code, $number_type, $features)
{
    $index =1;
    $size =100;
    $my_numbers = json_decode(list_owned_number($api_key,$api_secret,1,1));
    $nb_result = $my_numbers->count;
    //echo 'mon argument features vaut '.$features;
    //echo 'There is '.$nb_result.' answers availables';
    //echo ($index*10) < $my_numbers['count'] ;
    //echo '<pre>';

    //unset ($my_numbers);

    for ($index=1; (($index-1)*$size) < $nb_result ;$index=$index+1)
    {
       // echo 'I am inside the for<pre>';
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
                       // echo 'pas de features';
                        $my_good_numbers[] = $number;
                    } elseif ($features === 'VOICE' or $features === 'SMS' ){
                        //echo 'une feature et c est '.$features;
                        foreach ($number->features as $features_number)
                        {
                            if($features_number === $features )
                            {
                                    $my_good_numbers[] = $number;
                            }
                        }
                    } elseif ($features == 'SMS,VOICE'){
                        //echo 'là j ai 2 features';
                        if ($number->features == ["VOICE","SMS"]){
                            $my_good_numbers[] = $number;
                        }
                    }
                }
            }
            // is this number from the right type ?
            // is this number voice enabled ?

            //echo 'I am inside the foreach<pre>';
            //print_r($number->msisdn);
            //echo '<pre>';        
        }
        //echo '<pre>';
    }
    
    //echo '<pre><pre>';
    //print_r($my_good_numbers);
 // we see only the first 10 numbers. Need to manage the index of list_owned_number()
    $yourJson=json_encode($my_good_numbers);
    $yourJson = substr($yourJson, 1, strlen($yourJson) - 2);
    return '{'.$yourJson.'}';
}
/*

if ( isset( $_POST['country_code']) && isset( $_POST['number_type']))
{
    echo '<html>';
    echo '<head><title>Proxy config</title></head>';
    echo '<body> <form action="./index.php" method="post"> <p>country code <br><input type="text" name="country_code" /></p> <p>type<br><input type="text" name="number_type" /></p> <p>destination<br><input type="text" name="destination" /></p> <p><input type="submit" value=" OK " /></p>';
    echo '</form></body></html>'; 
    $resultat = In_my_numbers_is_there_a_good_one_available($_POST['country_code'], $_POST['number_type']);
    echo $_POST['number_type'];
    //$resultat = search_available_number($_POST['country_code'], $_POST['number_type']);
    echo '<pre>';
    print_r($resultat);
    echo '</pre>';


} else {

    echo '<html>';
    echo '<head><title>Proxy config</title></head>';
    echo '<body> <form action="./index.php" method="post"> <p>country code <br><input type="text" name="country_code" /></p> <p>type<br><input type="text" name="number_type" /></p> <p>destination<br><input type="text" name="destination" /></p> <p><input type="submit" value=" OK " /></p>';
    echo '</form></body></html>'; 
}
*/