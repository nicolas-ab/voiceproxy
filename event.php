<?php

//Retrieve the conversation ID from the JSON object sent to eventURL
$request = file_get_contents('php://input');
$fp = fopen("request.txt", 'a');
fwrite($fp,$request);
fclose($fp);
?>
