<?php

namespace eifachoeppis\Service;

class FileMySqlService implements FileService{
	
	private $pdo;
	
	public function __construct(\PDO $pdo){
		$this->pdo = $pdo;
	}
	
	public function saveToDatabase($name, $type, $size, $fileTmpName){
		$content = file_get_contents($fileTmpName);
		$statement = $this->pdo->prepare("INSERT INTO image (name, type, size, content) VALUES (?, ?, ?, ?)");
		$statement->bindValue(1, $name);
		$statement->bindValue(2, $type);
		$statement->bindValue(3, $size);
		$statement->bindValue(4, $content);
		$statement->execute();
	}
	
	public function loadFromDatabase($id){
		$statement = $this->pdo->prepare("SELECT type, content FROM image WHERE id=?");
		$statement->bindValue(1, $id);
		$statement->execute();
		$data = array();
		return $statement->fetchObject();
	}
	
	public function getIds(){
		$statement = $this->pdo->prepare("SELECT id, name FROM image");
		$statement->execute();
		$data = array();
		while($row = $statement->fetchObject()){
			$data[] = $row;
		}
		return $data;
	}
}
