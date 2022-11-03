<?php
//HEADERS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

$headers = apache_request_headers();
$res = array();

$body = json_decode(file_get_contents("php://input"), true);
if (isset($body['operation_type']) && isset($body['x']) && isset($body['y']) && gettype($body['x']) == 'integer' && gettype($body['y']) == 'integer') {
    // set response code - 200 OK
    http_response_code(200);

    extract($body);
    $string = $body['operation_type'];
    $variables = array();
    $sign = "";

    //the the values from the string
    for ($i = 0; $i < strlen($string); $i++) {
        if (in_array($string[$i], array("+", "-", "*"))) $sign = $string[$i];
        if (in_array(strtolower($string[$i]), array("x", "y"))) array_push($variables, $string[$i]);
    }

    //declare new variables
    $first_num = $variables[0];
    $second_num = $variables[1];

    if ($sign == '+') {
        $result = intval(${$first_num}) + intval(${$second_num});
    } elseif ($sign == '-') {
        $result = intval(${$first_num}) - intval(${$second_num});
    } elseif ($sign == '*') {
        $result = intval(${$first_num}) * intval(${$second_num});
    } else {
        $result = "Kindly pass in a valid operator";
    }

    $res["slackUsername"] = "dicodedev";
    $res["operation_type"] = $string;
    $res["result"] = $result;
}

echo json_encode($res);