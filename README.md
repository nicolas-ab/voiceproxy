# voiceproxy
PHP backend for Nexmo voice proxy

1) Installation and configuration
- download the project 
- edit the file _constants.php and document the credentials of your sql database
- rename the file htaccess to .htaccess
- deploy all the file except this readme file on a webserver.
- import the postman collection available on https://www.getpostman.com/collections/36b6aa214ecfcf389b75
- execute the request ‘database deployment’, you should get the answer: 
Tables created successfully
<BR>
If it is not the case, double check the credentials of your sql database and the url documented (variable env) in your Postman Environment

2) usage of the API Calls:

Please refers to the documentation available within the postman collection 
