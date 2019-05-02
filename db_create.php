<?php

require_once('db_functions.php');

/*
// Name of the file
$filename = 'nexmops_nicolas.sql';
// MySQL host
$mysql_host = 'localhost';
// MySQL username
$mysql_username = 'nexmops_nicolas';
// MySQL password
$mysql_password = '.V-3G-AK=[90';
// Database name
$mysql_database = 'nexmops_nicolas';
*/
// Connect to MySQL server
$myconnection = voiceproxy_connect();
// Select database

// DATABASE CREATION
// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file(CREATION_TABLE);
// Loop through each line
foreach ($lines as $line)
{
// Skip it if it's a comment
if (substr($line, 0, 2) == '--' || $line == '')
    continue;

// Add this line to the current segment
$templine .= $line;
// If it has a semicolon at the end, it's the end of the query
if (substr(trim($line), -1, 1) == ';')
{
    // Perform the query
    mysqli_query($myconnection ,$templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error($myconnection) . '<br /><br />');
    // Reset temp variable to empty
    $templine = '';
}
}
 echo "Tables created successfully<BR>";

 
mysqli_close($myconnection);








?>