<?php
/**
* Bot Class
*
* @version 1.0
* @copyright 2018 Foose Industries
*/
class Bot {
	/* Verify Token */
	private static $verifyToken;

	/* Page Access Token */
	private static $accessToken;

	/* API URL */
	private static $graphAPI = 'https://graph.facebook.com/v2.8';

	// constructor
	public function __construct($verifyToken, $accessToken, $graphAPI='') {
		self::$verifyToken = $verifyToken;
		self::$accessToken = $accessToken;

		if(!empty($graphAPI)) {
			self::$graphAPI = $graphAPI;
		}
	}

	public function run($r) {
		// check token at setup
		if ($_REQUEST['hub_verify_token'] === self::$verifyToken) {
		  echo $_REQUEST['hub_challenge'];
		  exit;
		}

		// handle bot's anwser
		$input = json_decode(file_get_contents('php://input'), true);

		$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
		$messageText = $input['entry'][0]['messaging'][0]['message']['text'];
		$recievedPayload = $input['entry'][0]['messaging'][0]['postback']['payload'];

		if(!empty(messageText)) {
			$reqBody = ['senderId'=>$senderId,'message'=>$messageText,'payload'=>$recievedPayload];
		
			$r($reqBody,$this);		
		}
	}

	public function sendResponse($response) {
		if(!empty($response)) {
			$response = json_encode($response, JSON_UNESCAPED_SLASHES);

			$ch = curl_init(self::$graphAPI.'/me/messages?access_token='.self::$accessToken);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

			curl_exec($ch);

			curl_close($ch);

			die();
		}
	}

	// response helper functions

	public function buildCard($title,$subtitle=null,$imageURL=null,$defaultAction=null,$buttons=null) {
		$card = ['title'=>$title];

		if(!empty(@$subtitle)) {
			$card['subtitle'] = $subtitle;
		}

		if(!empty(@$imageURL)) {
			$card['image_url'] = $imageURL;
		}

		if(!empty(@$defaultAction)) {
			$card['default_action'] = $defaultAction;
		}

		if(!empty(@$buttons)) {
			$card['buttons'] = $buttons;
		}

		return $card;
	}

	public function builAction($type='web_url',$url,$messengerExtensions=true,$heightRatio='tall',$fURL) {
		return [
			'type'=>$type,
			'url'=>@$url,
			'messenger_extensions'=>$messengerExtensions,
			'webview_height_ratio'=>$heightRatio,
			'fallback_url'=>@$fURL
		];
	}

	public function buildButton($title="Button",$type="web_url",$url,$payload=null) {
		$button = ['url'=>$url,'title'=>$title,'type'=>$type];

		if(!empty(@$payload)) {
			$button['payload'] = $payload;
		}

		return $button;
	}

	public function buildRichResponse($recipientID,$cards) {
		return [
			'recipient'=>['id'=>$recipientID],
			'message'=>[
				'attachment'=>[
					'type'=>'template',
					'payload'=>[
						'template_type'=>'generic',
						'elements'=>$cards
					]
				]
			]
		];
	}

	public function buildSimpleResponse($message='Default Message',$recipient) {
		return [
			'recipient'=>['id'=>$recipient],
			'message'=>['text'=>$message]
		];
	}
}
?>
