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
		$statement = $this->pdo->prepare("SELECT * FROM user WHERE email=? AND password=?");
		$statement->bindValue(1, $username);
		$statement->bindValue(2, $password);
		$statement->execute();
		 
		return $statement->rowCount() == 1;
	}
}

?>