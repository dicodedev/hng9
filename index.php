<?php
//HEADERS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

$headers = apache_request_headers();
$res = array();

$body = json_decode(file_get_contents("php://input"));

$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
fwrite($myfile, file_get_contents("php://input"));
fclose($myfile);

if (isset($body->operation_type) && isset($body->x) && isset($body->y) && gettype($body->x) == 'integer' && gettype($body->y) == 'integer') {
    // set response code - 200 OK
    http_response_code(200);

    if ($body->operation_type == '+') {
        $result = $body->x + $body->y;
    } elseif ($body->operation_type == '-') {
        $result = $body->x - $body->y;
    } elseif ($body->operation_type == '*') {
        $result = $body->x * $body->y;
    } else {
        $result = "Kindly pass in a valid operator";
    }

    $res["slackUsername"] = "dicodedev";
    $res["operation_type"] = "integer";
    $res["result"] = $result;
}

echo json_encode($res);