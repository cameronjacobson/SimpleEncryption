<?php

require_once(dirname(__DIR__).'/vendor/autoload.php');

use SimpleEncryption\SimpleEncryption;

$enc = new SimpleEncryption([
	'key'=>__DIR__.'/samplekeys/mytestkeys',
	'padding'=>OPENSSL_PKCS1_PADDING,
	'encrypt'=>function($string){
		return base64_encode($string);
	},
	'decrypt'=>function($string){
		return base64_decode($string);
	},
]);

// encrypt with private key
$encrypted = base64_encode($enc->encryptString('hello world',true));
echo PHP_EOL.'ENCRYPTED WITH PRIVATE KEY:'.PHP_EOL;
echo $encrypted.PHP_EOL;

// decrypt with public key
echo PHP_EOL.'DECRYPTED WITH PUBLIC KEY:'.PHP_EOL;
echo $enc->decryptString(base64_decode($encrypted),true).PHP_EOL;

// encrypt with public key
$encrypted = base64_encode($enc->encryptString('hello world'));
echo PHP_EOL.'ENCRYPTED WITH PUBLIC KEY:'.PHP_EOL;
echo $encrypted.PHP_EOL;

// decrypt with private key
echo PHP_EOL.'DECRYPTED WITH PRIVATE KEY:'.PHP_EOL;
echo $enc->decryptString(base64_decode($encrypted)).PHP_EOL;
echo PHP_EOL;
