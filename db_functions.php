<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once("_constants.php");

function voiceproxy_connect()
{
    $conn = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS,MYSQL_DB_NAME);
    if(! $conn ){
       die('Could not connect: '.mysqli_error());
    }
    return $conn;
}

function voiceproxy_discconnect($conn)
{
    mysqli_close($conn);
}

function voiceproxy_insert_association($conn, $lvn, $driver, $customer)
{

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
     }
     $sql = "INSERT INTO association(lvn, driver, customer) VALUES ('".$lvn."','".$driver."','".$customer."')";

     if (mysqli_query($conn, $sql)) {
     return 200;
     } else {
        echo "Error: " . $sql . "" . mysqli_error($conn);
     return 500;
     }
}

function voiceproxy_list_association($conn)
{
    $result= mysqli_query($conn, "SELECT * FROM association");

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            //echo "lvn: " . $row["lvn"]. " - agent " . $row["agent"]. "<br>";
            $list[]=$row;
        }
    } else {
        //echo "0 results";
        $list=NULL;
    }
    return $list;
}

function voiceproxy_get_association($conn, $lvn)
{
    $result= mysqli_query($conn, "SELECT driver, customer FROM `association` WHERE `lvn` LIKE '".$lvn."'");
    if(mysqli_num_rows($result) > 0)
    {
        return mysqli_fetch_assoc($result);
    }
    else
    {
        return NULL;
    }
}

function voiceproxy_update_association($conn, $lvn, $driver, $customer)
{   

    if ($conn->connect_error) {
        die("Connection failed: ".$conn->connect_error);
     }
     $sql = "UPDATE `association` SET `driver` = '".$driver."', `customer` = '".$customer."'  WHERE `association`.`lvn` LIKE '".$lvn."'";

     // echo '<BR> request: '.$sql.'<BR>';
     if (mysqli_query($conn, $sql)) {
        // echo "uuid ".$uuid." updated successfully: ". mysqli_error($conn);
        return 200;
     } else {
        echo "Error: " . $sql . "" . mysqli_error($conn);
        return 500;
     }
}

function voiceproxy_delete_association($conn, $lvn)
{

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
     }
     $sql = "DELETE FROM `association` WHERE `lvn` LIKE '".$lvn."'";

     if (mysqli_query($conn, $sql)) {
     return 200;
     } else {
        echo "Error: " . $sql . "" . mysqli_error($conn);
     return 500;
     }
}