<?php

namespace eifachoeppis\Service;

interface FileService{
	public function saveToDatabase($name, $type, $size, $content);
	public function loadFromDatabase();
}

?>