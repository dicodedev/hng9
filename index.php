<?php
//HEADERS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

$headers = apache_request_headers();
$res = array();

$input = file_get_contents("php://input");
$body = json_decode($input, true);

$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
fwrite($myfile, $input.substr($input, 0, 5));
fclose($myfile);

// if ((isset($body['operation_type']) && isset($body['x']) && isset($body['y']) && gettype($body['x']) == 'integer' && gettype($body['y']) == 'integer') || substr($body, 0, )) {
// set response code - 200 OK
http_response_code(200);
if (substr($input, 0, 5) == "opera") {
    $body = explode("&", $input);
    $operation_type = $body[0];
    $x = $body[1];
    $y = $body[2];
} else {
    extract($body);
}


$string = $operation_type;
$lstring = "task " . strtolower($string);
$variables = array();
$sign = "";

//the the values from the string
for ($i = 0; $i < strlen($string); $i++) {
    if (in_array(strtolower($string[$i]), array("x", "y"))) array_push($variables, $string[$i]);
    if (in_array($string[$i], array("+", "-", "*"))) $sign = $string[$i];
}

//operation check
if (strrpos($lstring, "add") || strrpos($lstring, "plus") || strrpos($lstring, "sum")) $sign = "+";
if (strrpos($lstring, "subtract") || strrpos($lstring, "minus") || strrpos($lstring, "remove")) $sign = "-";
if (strrpos($lstring, "multipl") || strrpos($lstring, "product") || strrpos($lstring, "times")) $sign = "*";

//declare new variables
if (!strrpos($lstring, "from")) {
    //it doesn't contain a from statement
    if (in_array($string, array("addition", "subtraction", "multiplication"))) {
        $first_num = "x";
        $second_num = "y";

        //set the sign
        if ($string == "addition") $sign = "+";
        if ($string == "subtraction") $sign = "-";
        if ($string == "multiplication") $sign = "*";
    } else {
        $first_num = $variables[0];
        $second_num = $variables[1];
    }
} else {
    //if it contain a from statement, interchnage the numbers
    $first_num = $variables[1];
    $second_num = $variables[0];
}

//actual calculation
if ($sign == '+') $result = intval(${$first_num}) + intval(${$second_num});
elseif ($sign == '-') $result = intval(${$first_num}) - intval(${$second_num});
elseif ($sign == '*') $result = intval(${$first_num}) * intval(${$second_num});
else $result = "Kindly pass in a valid operator";

//retun values
$res["slackUsername"] = "dicodedev";
$res["operation_type"] = $string;
$res["result"] = $result;
// }
echo json_encode($res);
// echo "slackUsername=" . $res["slackUsername"] . "&operation_type=" . $string . "&result=" . $result;