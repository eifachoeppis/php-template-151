<?php

namespace eifachoeppis\Controller;

use eifachoeppis\Service\FileService;
use eifachoeppis\Session;
use eifachoeppis\Entity\ImageEntity;

class FileController 
{
  /**
   * @var ihrname\SimpleTemplateEngine Template engines to render output
   */
  private $template;
  private $fileService;
  private $session;
  
  /**
   * @param ihrname\SimpleTemplateEngine
   */
  public function __construct(\Twig_Environment $template, FileService $fileService, Session $session)
  {
  	 $this->fileService = $fileService;
     $this->template = $template;
     $this->session = $session;
  }

  public function showUpload() {
 	$this->session->generateToken();
    echo $this->template->render("upload.html.twig");
  }
  
  

  public function upload($file, $data) {
  	if (!$this->session->checkValue("csrf", $data["csrf"])){
  		header("Location: /");
  		return;
  	}
  	$image = new ImageEntity();
  	$image->setName($file['fileToUpload']['name']);
  	$image->setType($file['fileToUpload']['type']);
  	$image->setSize($file['fileToUpload']['size']);
  	$fileTmpName = $file['fileToUpload']['tmp_name'];	
  	
  	//Überprüfen ob das File ein Bild ist
  	if (preg_match("/image\//", $image->getType())){
  		$this->fileService->saveToDatabase($image, $fileTmpName);
  		header("Location: /");
  	}
  	else{
  		$this->showUpload();
  	}
  	
  }
  
  public function showFiles() {
  	echo $this->template->render("images.html.twig", ["data" => $this->fileService->getIds()]);
  }
  
  public function getImage($id) {
  	$image = $this->fileService->loadFromDatabase($id);
  	header("Content-Type: " . $image->getType());
  	echo $image->getContent();
  
  }
}
