<?php

namespace eifachoeppis\Entity;

class UserEntity{
	private $id;
	private $email;
	private $password;
	private $activationCode;
	private $passwordResetCode;
	
	public function __construct($email, $password){
		$this->email = $email;
		$this->password = $password;
		$this->activationCode = com_create_guid();
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function getPassword(){
		return $this->password;
	}
	
	public function getActivationCode(){
		return $this->activationCode;
	}
	
	public function getPasswordResetCode(){
		return $this->passwordResetCode;
	}
	
	public function setEmail($email){
		$this->email = $email;
	}
	
	public function setPassword($password){
		$this->password = $password;
	}
	
	public function setActivationCode($activationCode){
		$this->activationCode = $activationCode;
	}
	
	public function setPasswordResetCode($passwordResetCode){
		$this->passwordResetCode = $passwordResetCode;
	}
}

?>