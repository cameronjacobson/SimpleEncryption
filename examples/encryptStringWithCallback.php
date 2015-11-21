<?php

require_once(dirname(__DIR__).'/vendor/autoload.php');

use SimpleEncryption\SimpleEncryption;

$enc = new SimpleEncryption([
	'key'=>__DIR__.'/samplekeys/mytestkeys',
	'padding'=>OPENSSL_PKCS1_PADDING,
	'beforeencrypt'=>function($value){
		return json_encode($value);
	},
	'afterencrypt'=>function($value){
		return base64_encode($value);
	},
	'beforedecrypt'=>function($value){
		return base64_decode($value);
	},
	'afterdecrypt'=>function($value){
		return json_decode($value,true);
	},
]);

// encrypt with private key
$encrypted = $enc->encrypt(array('hello world'),true);
echo PHP_EOL.'ENCRYPTED WITH PRIVATE KEY:'.PHP_EOL;
echo $encrypted.PHP_EOL;

// decrypt with public key
echo PHP_EOL.'DECRYPTED WITH PUBLIC KEY:'.PHP_EOL;
echo $enc->decrypt($encrypted,true).PHP_EOL;

// encrypt with public key
$encrypted = $enc->encrypt(array('hello world'));
echo PHP_EOL.'ENCRYPTED WITH PUBLIC KEY:'.PHP_EOL;
echo $encrypted.PHP_EOL;

// decrypt with private key
echo PHP_EOL.'DECRYPTED WITH PRIVATE KEY:'.PHP_EOL;
echo $enc->decrypt($encrypted).PHP_EOL;
echo PHP_EOL;
