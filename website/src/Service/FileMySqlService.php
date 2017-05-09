<?php

namespace eifachoeppis\Service;

class FileMySqlService implements FileService{
	
	private $pdo;
	
	public function __construct(\PDO $pdo){
		$this->pdo = $pdo;
	}
	
	public function saveToDatabase($name, $type, $size, $content){
		$statement = $this->pdo->prepare("INSERT INTO image (name, type, size, content) VALUES (?, ?, ?, ?)");
		$statement->bindValue(1, $name);
		$statement->bindValue(2, $type);
		$statement->bindValue(3, $size);
		$statement->bindValue(4, $content);
		$statement->execute();
	}
	
	public function loadFromDatabase(){
		$statement = $this->pdo->prepare("SELECT * FROM image");
		$statement->execute();
		$data = array();
		while($row = $statement->fetchObject()){
			$data[] = $row;
		}
		return $data;
	}
}

?>