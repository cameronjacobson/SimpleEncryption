<?php

require_once(dirname(__DIR__).'/vendor/autoload.php');

use SimpleEncryption\SimpleEncryption;

$enc = new SimpleEncryption([
	'key'=>__DIR__.'/samplekeys/mytestkeys',
	'padding'=>OPENSSL_PKCS1_PADDING
]);

$filename = __DIR__.'/samplefiles/helloworld.txt';

// encrypt with private key
$encrypted = $enc->encryptFile($filename,true);
echo PHP_EOL.'ENCRYPTED WITH PRIVATE KEY:'.PHP_EOL;
echo base64_encode($encrypted).PHP_EOL;

file_put_contents($filename.'.encrypted',$encrypted);

// decrypt with public key
echo PHP_EOL.'DECRYPTED WITH PUBLIC KEY:'.PHP_EOL;
echo $enc->decryptFile($filename.'.encrypted',true).PHP_EOL;

// encrypt with public key
$encrypted = $enc->encryptFile($filename);
echo PHP_EOL.'ENCRYPTED WITH PUBLIC KEY:'.PHP_EOL;
echo base64_encode($encrypted).PHP_EOL;

file_put_contents($filename.'.encrypted',$encrypted);

// decrypt with private key
echo PHP_EOL.'DECRYPTED WITH PRIVATE KEY:'.PHP_EOL;
echo $enc->decryptFile($filename.'.encrypted').PHP_EOL;
echo PHP_EOL;
