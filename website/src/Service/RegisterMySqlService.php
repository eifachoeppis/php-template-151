<?php

namespace eifachoeppis\Service;

class RegisterMySqlService implements RegisterService{
	
	private $pdo;
	
	/**
	 * @param \PDO
	 */
	public function __construct(\PDO $pdo){
		$this->pdo = $pdo;
	}
	
	public function createUser($username, $password, $passwordConfirm){
		if ($password != $passwordConfirm){
			return false;
		}
		
		$statement = $this->pdo->prepare("SELECT * FROM user WHERE email=?");
		$statement->bindValue(1, $username);
		$statement->execute();
		if ($statement->rowCount() > 0){
			return false;
		}
		$activationCode = $this->createGuid();
		$statement = $this->pdo->prepare("INSERT INTO user (email, password, activationCode) VALUES (?, ?, ?)");
		$statement->bindValue(1, $username);
		$statement->bindValue(2, password_hash($password, PASSWORD_DEFAULT));
		$statement->bindValue(3, $activationCode);
		$statement->execute();
		return $activationCode;
	}
	
	public function activateUser($guid){
		$statement = $this->pdo->prepare("SELECT id FROM user WHERE activationCode=?");
		$statement->bindValue(1, $guid);
		$statement->execute();
		if ($statement->rowCount() == 1){
			$userId = $statement->fetchObject()->id;
			$statement = $this->pdo->prepare("UPDATE user SET activationCode='' WHERE id=?");
			$statement->bindValue(1, $userId);
			$statement->execute();
			return true;
		}
		return false;
	}
	
	public function checkPasswordReset($guid){
		$statement = $this->pdo->prepare("SELECT * FROM password_reset WHERE reset_code=?");
		$statement->bindValue(1, $guid);
		$statement->execute();
		if ($statement->rowCount() == 1){
			if (strtotime($statement->fetchObject()->reset_time) + 1800 > time()){ //passwort reset nur 30 min gültig
				return true;
			}
		}
		return false;
	}
	
	public function resetPassword($guid, $password){
		$statement = $this->pdo->prepare("SELECT * FROM password_reset WHERE reset_code=?");
		$statement->bindValue(1, $guid);
		$statement->execute();
		if ($statement->rowCount() == 1){
			$userId = $statement->fetchObject()->user_id;
			$statement = $this->pdo->prepare("UPDATE user SET password=? WHERE id=?");
			$statement->bindValue(1, password_hash($password, PASSWORD_DEFAULT));
			$statement->bindValue(2, $userId);
			$statement->execute();
			$statement = $this->pdo->prepare("DELETE FROM password_reset WHERE user_id=?");
			$statement->bindValue(1, $userId);
			$statement->execute();
			return true;
		}
		return false;
	}
	
	public function createResetRequest($email){
		$statement = $this->pdo->prepare("SELECT * FROM user WHERE email=?");
		$statement->bindValue(1, $email);
		$statement->execute();
		if ($statement->rowCount() == 1){
			$userid = $statement->fetchObject()->id;
			$statement = $this->pdo->prepare("DELETE FROM password_reset WHERE user_id=?");
			$statement->bindValue(1, $userid);
			$statement->execute();
			$resetCode = $this->createGuid();
			$currentTime = date('Y-m-d H:i:s');
			$statement = $this->pdo->prepare("INSERT INTO password_reset (user_id, reset_code, reset_time) VALUES (?, ?, ?)");
			$statement->bindValue(1, $userid);
			$statement->bindValue(2, $resetCode);
			$statement->bindValue(3, $currentTime);
			$statement->execute();
			return $resetCode;
		}
		return false;
	}
	
	private function createGuid(){
		if (function_exists('com_create_guid') === true){
			return trim(com_create_guid(), '{}');
		}
		$data = openssl_random_pseudo_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
}
