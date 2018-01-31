# PHP Messenger Bot
PHP simple Facebook Messenger Bot template


## Setup

Use this file on a webserver where your messenger bot logic will be located.  

## Use

The first parameter when created a new bot instance is the verify token, the second is the page access token.  A third and optional parameter is the Graph API URL, by default it is set to version 2.8.  

```php
$bot = new Bot('verify_token','access_token');

$bot->run(function($reqBody,$bot) {
	if(!empty($reqBody) && !empty($reqBody['message'])) {
		$bot->sendResponse($bot->buildSimpleResponse($reqBody['senderId'],'mesage text');
	}
});
```

When running the bot, the bot instance will return the request body from the request and an instance of itself.  Use the returned instance to build and send responses

## Responses

### Sending Responses

```php
$bot->sendResponse($resp);
```

### Simple Response

```php
$resp = $bot->buildSimpleResponse('recipient_id','Message Text');
```

### Card

The cards must be in an array even if only one card is needed.  To show a carousel response, add create multiple cards and add them to this array.

Note, a 'title' and 'subttile' are required.

When adding buttons, they must be in an array even if only one is needed.

```php
$card = $bot->buildCard('title','subtitle','image_url',$default_action,$buttons);
$resp = $bot->buildRichResponse('recpient_id',[$card]);
```

### Buttons

```php
$btn = $bot->buildButton();

```

List view support coming soon...

Refer to https://developers.facebook.com/docs/messenger-platform for more help on how messenger bots work.  
