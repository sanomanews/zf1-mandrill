# zf1-mandrill

This is a very basic Mandrill transport wrapper for Zend Framework 1.x.

## Installation

```
composer require sanoma/zf1-mandrill
```

## Usage

Configure Zend to use the transport using the following snippet:

```php
<?php

$apiKey = 'MANDRILL_API_TOKEN';

$transport = new Zend_Mail_Transport_Mandrill($apiKey, [
	'track_opens'  => true,
	'track_clicks' => true,
	// any other default options you want to add to each message
]);

Zend_Mail::setDefaultTransport($transport);
```

After this, just use `Zend_Mail` as usual.

## License

MIT
