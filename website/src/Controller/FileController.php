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
  	$image->setName($data['imageName']);
  	$image->setType($file['fileToUpload']['type']);
  	$image->setSize($file['fileToUpload']['size']);
  	$fileTmpName = $file['fileToUpload']['tmp_name'];	
  	  	
  	//Überprüfen ob das File ein Bild ist
  	if (preg_match("/^[a-zA-Z0-9_]+$/", $image->getName()) && preg_match("/image\/png|jpg|gif|jpeg/", $image->getType())){
  		$this->fileService->saveToDatabase($image, $fileTmpName);
  		echo $this->template->render("uploadinfo.html.twig");
  	}
  	else{
  		echo $this->template->render("upload.html.twig", ["error" => "Image must be png, jpg, jpeg or gif. The filename can only contain letters and numbers"]);
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
