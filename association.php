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

if(!(isset( $_GET['api_key']) && isset( $_GET['api_secret'])&& isset( $_GET['action'])))
{
    echo 'error: you need to pass arguments api_key, api_secret and action';
    exit(0);
} 
$api_key=$_GET['api_key'];
$api_secret=$_GET['api_secret'];
$action= $_GET['action'];

switch($action){
    case 'insert':
        if(!(isset( $_GET['lvn']) && isset( $_GET['agent'])))
        {
            echo 'error: you need to pass arguments lvn and agent';
            exit(0);
        }
        $lvn= $_GET['lvn'];
        $agent= $_GET['agent'];
        $conn=voiceproxy_connect();
        $http_code=voiceproxy_insert_association($conn, $lvn, $agent);
        voiceproxy_discconnect($conn);
        http_response_code($http_code);
        exit(0);
    break;
    case 'update':
        if(!(isset( $_GET['lvn']) && isset( $_GET['agent'])))
        {
            echo 'error: you need to pass arguments lvn and agent';
            exit(0);
        }
        $lvn= $_GET['lvn'];
        $agent= $_GET['agent'];
        $conn=voiceproxy_connect();
        $http_code=voiceproxy_update_association($conn, $lvn, $agent);
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
