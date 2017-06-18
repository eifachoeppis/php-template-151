<?php

namespace eifachoeppis\Service;

use eifachoeppis\Entity\UserEntity;

class LoginMySqlService implements LoginService{
	
	private $pdo;
	
	/**
	 * @param ihrname\SimpleTemplateEngine
	 * @param \PDO
	 */
	public function __construct(\PDO $pdo){
		$this->pdo = $pdo;
	}
	
	public function authenticate(UserEntity $userToAuthenticate){
		$statement = $this->pdo->prepare("SELECT * FROM user WHERE email=?");
		$statement->bindValue(1, $userToAuthenticate->getEmail());
		$statement->execute();
		$user = $statement->fetchObject();
		if ($statement->rowCount() < 1 || $user->activationCode){
			return false;
		}
		if (password_verify($userToAuthenticate->getPassword(), $user->password)){
			$id = $user->id;
			$email = $user->email;
			$oUser = new UserEntity();
			$oUser->setEmail($email);
			$oUser->setId($id);
			return $oUser;
		}
	}
}
