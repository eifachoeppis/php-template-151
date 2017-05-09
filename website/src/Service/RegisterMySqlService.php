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
		$statement = $this->pdo->prepare("INSERT INTO user (email, password) VALUES (?, ?)");
		$statement->bindValue(1, $username);
		$statement->bindValue(2, password_hash($password, PASSWORD_DEFAULT));
		$statement->execute();
		return true;
	}
}

?>