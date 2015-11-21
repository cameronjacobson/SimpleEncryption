<?php

namespace SimpleEncryption;

class SimpleEncryption
{
	private $key;

	public function __construct(array $params){
		$this->padding = $params['padding'];
		$this->privatekey = $this->getPrivateKey($params['key']);
		$this->publickey = $this->getPublicKey($params['key']);

		if(!empty($params['encrypt']) && is_callable($params['encrypt'])){
			$this->encrypt = $params['encrypt'];
		}
		else{
			$this->encrypt = function($x){return $x;};
		}

		if(!empty($params['decrypt']) && is_callable($params['decrypt'])){
			$this->decrypt = $params['decrypt'];
		}
		else{
			$this->decrypt = function($x){return $x;};
		}
	}

	public function encryptString($string,$private = false){
		if($private){
			openssl_private_encrypt($string,$encrypted,$this->privatekey,$this->padding);
		}
		else{
			openssl_public_encrypt($string,$encrypted,$this->publickey,$this->padding);
		}
		return $this->encrypt->__invoke($encrypted);
	}

	public function decryptString($string,$public=false){
		$string = $this->decrypt->__invoke($string);
		if($public){
			openssl_public_decrypt($string,$decrypted,$this->publickey,$this->padding);
		}
		else{
			openssl_private_decrypt($string,$decrypted,$this->privatekey,$this->padding);
		}
		return $decrypted;
	}

	public function encryptFile($filename,$private = false){
		if(!file_exists($filename)){
			throw new \Exception('file '.$filename.' does not exist');
		}
		return $this->encryptString(file_get_contents($filename),$private);
	}

	public function decryptFile($filename,$public=false){
		if(!file_exists($filename)){
			throw new \Exception('file '.$filename.' does not exist');
		}
		return $this->decryptString(file_get_contents($filename),$public);
	}

	public static function createKeyPair($filename){
		if(file_exists(dirname($filename)) && is_dir(dirname($filename))){
			$res = openssl_pkey_new(array(
				'digest_alg'=>'sha512',
				'private_key_bits'=>4096,
				'private_key_type'=>OPENSSL_KEYTYPE_RSA,
				'encrypt_key_cipher'=>OPENSSL_CIPHER_AES_256_CBC
			));
			openssl_pkey_export($res,$priv);
			file_put_contents($filename,$priv);
			$pub = openssl_pkey_get_details($res);
			file_put_contents($filename.'.pub',$pub['key']);
		}
	}

	private function getPublicKey($key){
		$key = $key.'.pub';
		$key = $this->getPrivateKey($key);
		return $key;
	}

	private function getPrivateKey($key){
		if(!file_exists($key)){
			$key = '/home/'.get_current_user().'/.ssh/'.$key;
			if(!file_exists($key)){
				throw new \Exception('key '.$key.' not found');
			}
		}
		return file_get_contents($key);
	}
}
