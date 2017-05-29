<?php
namespace eifachoeppis;

class Session{
	
	public function __construct(){
		session_start();
	}
	
	public function get($key){
		if (array_key_exists($key, $_SESSION)){
			return $_SESSION[$key];
		}
		return null;
	}
	
	public function set($key, $value){
		$_SESSION[$key] = $value;
	}
	
	public function unset($key){
		unset($_SESSION[$key]);
	}
	
	public function has($key){
		return array_key_exists($key, $_SESSION);
	}
	
	public function generateToken(){
		$_SESSION["csrf"] = base64_encode(random_bytes(32));
	}
	
	public function regenerateId(){
		session_regenerate_id();
	}
	
	public function checkValue($key, $value){
		if ($this->get($key) == $value){
			return true;
		}
		return false;
	}
}
