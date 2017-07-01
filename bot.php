<?php
// parameters
$hubVerifyToken = ''; // set
$accessToken = ''; // set

// check token at setup
if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}

// handle bot's anwser
$input = json_decode(file_get_contents('php://input'), true);

$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageText = $input['entry'][0]['messaging'][0]['message']['text'];
$recievedPayload = $input['entry'][0]['messaging'][0]['postback']['payload'];

$response = '';

// default answer
$answer = "I don't understand. Ask me 'hi'.";

if($recievedPayload != null) {
  // implement response from recieved payload
} else {
  if($messageText == "hi" || $messageText == "Hi") {
      $answer = "Hi";
  } 
}

$response = json_encode($response, JSON_UNESCAPED_SLASHES);

$ch = curl_init('https://graph.facebook.com/v2.8/me/messages?access_token='.$accessToken);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

if(!empty($input['entry'][0]['messaging'][0]['message']) || !empty($recievedPayload)) {
  curl_exec($ch);
} 

curl_close($ch);
?>