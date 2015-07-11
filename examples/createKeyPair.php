<?php

require_once(dirname(__DIR__).'/vendor/autoload.php');

use SimpleEncryption\SimpleEncryption;

$filename = __DIR__.'/samplekeys/mytestkeys';
SimpleEncryption::createKeyPair($filename);

echo file_get_contents($filename).PHP_EOL;
echo file_get_contents($filename.'.pub').PHP_EOL;


