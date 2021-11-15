<?php

//step 1: create json-object from incoming http request

$myObject = array(
	'proxyJsonObjectBase64' => base64_encode(json_encode($_SERVER)),
	'bodyJsonObjectBase64' => base64_encode(file_get_contents('php://input'))
);

$payload = json_encode($myObject, JSON_FORCE_OBJECT);


//step 2: send request to webservice and get response

$url = 'https://fhir-proxy-zwc.live.wem.io/webservices/proxy/endPoint';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
$result = curl_exec($ch);
curl_close($ch);

if ($result === FALSE) {
	echo 'error';
	/* Handle error */ 
	var_dump($result);
	die();
}

//step 3: create response

else{
	$result_array = json_decode($result, true);
	$responseBase64 = $result_array['responseBase64'];
	$responseJson = base64_decode($responseBase64);
	
	echo($responseJson);
	header("Content-type:application/json");
}

?>
