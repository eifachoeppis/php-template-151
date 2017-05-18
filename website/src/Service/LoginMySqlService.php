<?php

namespace eifachoeppis\Service;

class LoginMySqlService implements LoginService{
	
	private $pdo;
	
	/**
	 * @param ihrname\SimpleTemplateEngine
	 * @param \PDO
	 */
	public function __construct(\PDO $pdo){
		$this->pdo = $pdo;
	}
	
	public function authenticate($username, $password){
		$statement = $this->pdo->prepare("SELECT * FROM user WHERE email=?");
		$statement->bindValue(1, $username);
		$statement->execute();
		$user = $statement->fetchObject();
		if ($statement->rowCount() < 1 || $user->activationCode){
			return false;
		}
		return password_verify($password, $user->password);
	}
}

?>