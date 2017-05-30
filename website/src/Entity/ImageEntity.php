<?php

namespace eifachoeppis\Entity;

class ImageEntity{
	private $id;
	private $name;
	private $type;
	private $size;
	private $content;

	public function __construct(){
		
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function getType(){
		return $this->type;
	}

	public function getSize(){
		return $this->size;
	}

	public function getContent(){
		return $this->content;
	}
	
	public function setId($id){
		$this->id = $id;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function setSize($size){
		$this->size = $size;
	}

	public function setContent($content){
		$this->content = $content;
	}
}
?>