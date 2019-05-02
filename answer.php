
<?php
require_once("db_functions.php");
//Retrieve the conversation ID from the JSON object sent to eventURL

$method = $_SERVER['REQUEST_METHOD'];

$request = array_merge($_GET, $_POST);
$from=NULL;
$to=NULL;
$uuid=NULL;


//print_r($request);


if(!(isset( $_GET['from']) && isset( $_GET['uuid'])&& isset( $_GET['to'])))
{
    echo 'error: this ressource needs to be called through the answer callback url of a Nexmo Voice Application';
    exit(0);
} 
$from=$_GET['from'];// number calling (customer)
$to=$_GET['to'];// number called (Nexmo LVN)
$uuid= $_GET['uuid'];

$conn=voiceproxy_connect();
$agent_number=voiceproxy_get_association($conn, $to);

//print_r($agent_number);
if(is_null($agent_number))
{
    // means we did not find any agent phone number in the DB configured for this LVN . We gently close the inbound call
    $ncco = '[
        {
            "action": "talk",
            "text": "Sorry, there is no agent configured for this phone number"
        }
    ]';

} else {
    //to do: debugg ncco call without agent, document agent in the DB and test call with agent.
    $ncco = '[
        {   
            "timeout": 20,
            "action": "talk",
            "text": "Please wait while we connect you."
        },
        {
            "action": "connect",
            "timeout": 20,
            "from": "'.$to.'",
            "endpoint": [
                {
                    "type": "phone",
                    "number": "'.$agent_number.'"
                }
            ]
        }
    ]';

}
//http_response_code(200);
header('Content-Type: application/json');
echo $ncco;
