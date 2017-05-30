<?php

namespace eifachoeppis\Service;

use eifachoeppis\Entity\ImageEntity;

class FileMySqlService implements FileService{
	
	private $pdo;
	
	public function __construct(\PDO $pdo){
		$this->pdo = $pdo;
	}
	
	public function saveToDatabase($image, $fileTmpName){
		$content = file_get_contents($fileTmpName);
		try{
			$this->pdo->beginTransaction();
			$statement = $this->pdo->prepare("INSERT INTO image (name, type, size, content) VALUES (?, ?, ?, ?)");
			$statement->bindValue(1, $image->getName());
			$statement->bindValue(2, $image->getType());
			$statement->bindValue(3, $image->getSize());
			$statement->bindValue(4, $content);
			$statement->execute();
			$this->pdo->commit();
		}catch (\PDOException $e){
			$this->pdo->rollBack();
		}
		
	}
	
	public function loadFromDatabase($id){
		$statement = $this->pdo->prepare("SELECT type, content FROM image WHERE id=?");
		$statement->bindValue(1, $id);
		$statement->execute();
		if ($statement->rowCount() == 1){
			$row = $statement->fetchObject();
			$type = $row->type;
			$content = $row->content;
			$image = new ImageEntity();
			$image->setType($type);
			$image->setContent($content);
			return $image;
		}
	}
	
	public function getIds(){
		$statement = $this->pdo->prepare("SELECT id, name FROM image");
		$statement->execute();
		$data = array();
		while($row = $statement->fetchObject()){
			$image = new ImageEntity();
			$image->setId($row->id);
			$image->setName($row->name);
			$data[] = $image;
		}
		return $data;
	}
}
