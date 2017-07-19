# PHP Messenger Bot
PHP simple Facebook Messenger Bot template


## Setup

### Token
Set Facebook Graph API token.  

```php
$accessToken = ''; // set
```

### API Version

Check Graph API version in the URL and make sure it's current.  

```php
$ch = curl_init('https://graph.facebook.com/v2.8/me/messages?access_token='.$accessToken);
```

### Verify Token

Set verify token from Facebook Developers Console.  

```php
$hubVerifyToken = ''; // set
```
