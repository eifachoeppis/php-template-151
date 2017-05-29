<?php

namespace eifachoeppis\Controller;

use eifachoeppis\Service\FileService;
use eifachoeppis\Session;

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
  	$fileName = $file['fileToUpload']['name'];
  	$fileType = $file['fileToUpload']['type'];
  	$fileSize = $file['fileToUpload']['size'];
  	$fileTmpName = $file['fileToUpload']['tmp_name'];
  	
  	//Überprüfen ob das File ein Bild ist
  	if (preg_match("/image\/?png|jpg|jpeg|gif|svg/", $fileType)){
  		$this->fileService->saveToDatabase($fileName, $fileType, $fileSize, $fileTmpName);
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
  	header("Content-Type: " . $image->type);
  	echo $image->content;
  
  }
}
