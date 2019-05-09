<?php
require_once("db_functions.php");
//Retrieve the conversation ID from the JSON object sent to eventURL

$method = $_SERVER['REQUEST_METHOD'];

$request = array_merge($_GET, $_POST);
$lvn=NULL;
$agent=NULL;
$action=NULL;
$api_key=NULL;
$api_secret=NULL;
//print_r($request);

if(!(isset( $request['api_key']) && isset( $request['api_secret'])&& isset( $request['action'])))
{
    echo 'error: you need to pass arguments api_key, api_secret and action';
    exit(0);
} 
$api_key=$request['api_key'];
$api_secret=$request['api_secret'];
$action= $request['action'];

switch($action){
    case 'insert':
        if(!(isset( $request['lvn']) && isset( $request['driver']) && isset( $request['customer'])))
        {
            echo 'error: you need to pass arguments customer, lvn and driver';
            exit(0);
        }
        $lvn= $request['lvn'];
        $driver= $request['driver'];
        $customer = $request['customer'];
        $conn=voiceproxy_connect();
        $http_code=voiceproxy_insert_association($conn, $lvn, $driver, $customer);
        voiceproxy_discconnect($conn);
        http_response_code($http_code);
        exit(0);
    break;
    case 'update':
    if(!(isset( $request['lvn']) && isset( $request['driver']) && isset( $request['customer'])))
        {
            echo 'error: you need to pass arguments customer, lvn and driver';
            exit(0);
        }
        $lvn= $request['lvn'];
        $driver= $request['driver'];
        $customer = $request['customer'];
        $conn=voiceproxy_connect();
        $http_code=voiceproxy_update_association($conn, $lvn, $driver, $customer);
        voiceproxy_discconnect($conn);
        http_response_code($http_code);
        exit(0);
    break;
    case 'delete':
    if( !(isset( $request['lvn']) ) ) 
        {
            echo 'error: you need to pass arguments lvn';
            exit(0);
        }
        $lvn= $request['lvn'];
        $conn=voiceproxy_connect();
        $http_code=voiceproxy_delete_association($conn, $lvn);
        voiceproxy_discconnect($conn);
        http_response_code($http_code);
        exit(0);
    break;
    case 'list':
    $conn=voiceproxy_connect();
    $result=voiceproxy_list_association($conn);
    $json=json_encode($result);
    voiceproxy_discconnect($conn);
    break;
}

echo $json;
?>
