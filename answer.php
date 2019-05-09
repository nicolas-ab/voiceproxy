
<?php
require_once("db_functions.php");
//Retrieve the conversation ID from the JSON object sent to eventURL

$method = $_SERVER['REQUEST_METHOD'];

$request = array_merge($_GET, $_POST);
$from=NULL;
$to=NULL;
$uuid=NULL;



if(!(isset( $request['from']) && isset( $request['uuid'])&& isset( $request['to'])))
{
    echo 'error: this ressource needs to be called through the answer callback url of a Nexmo Voice Application';
    exit(0);
} 
$from=$request['from'];// number calling (customer)
$to=$request['to'];// number called (Nexmo LVN)
$uuid= $request['uuid'];

$conn=voiceproxy_connect();
//to do: voiceproxy_get_association return now 2 elements: driver phone number and customer phone number

//we get an array with 'driver' and 'customer' key containing the phone numbers
$array_numbers=voiceproxy_get_association($conn, $to);
if(is_null($array_numbers))
{//there is no association configured for this LVN
    $ncco = '[
        {
            "action": "talk",
            "text": "Sorry, there is no association configured for the phone number you have dialed"
        }
    ]';
}

if($from==$array_numbers['driver'])
{// if the calling if the driver, we will forward the call to the customer. 
    $ncco = '[
        {   
            "timeout": 20,
            "action": "talk",
            "text": "Please wait while we connect you to your customer."
        },
        {
            "action": "connect",
            "timeout": 20,
            "from": "'.$to.'",
            "endpoint": [
                {
                    "type": "phone",
                    "number": "'.$array_numbers['customer'].'"
                }
            ]
        }
    ]';
} else {
    // Otherwise, it is a customer phone number or an unknown number, we will forward the call to the driver
    $ncco = '[
        {   
            "timeout": 20,
            "action": "talk",
            "text": "Please wait while we connect you to your driver."
        },
        {
            "action": "connect",
            "timeout": 20,
            "from": "'.$to.'",
            "endpoint": [
                {
                    "type": "phone",
                    "number": "'.$array_numbers['driver'].'"
                }
            ]
        }
    ]';
}

header('Content-Type: application/json');
echo $ncco;
