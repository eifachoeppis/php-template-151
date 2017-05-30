<?php

namespace eifachoeppis\Service;

interface FileService{
	public function saveToDatabase($image, $content);
	public function getIds();
	public function loadFromDatabase($id);
}
