<?php
//HEADERS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

$headers = apache_request_headers();
$res = array();

// set response code - 200 OK
http_response_code(200);

$res["slackUsername"] = "dicodedev";
$res["backend"] = true;
$res["bio"] = "I'm a Software Engineer (Vuejs, Laravel/PHP)";

echo json_encode($res);