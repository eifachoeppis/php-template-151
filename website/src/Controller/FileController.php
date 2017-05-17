<?php

namespace eifachoeppis\Controller;

use eifachoeppis\Service\FileService;

class FileController 
{
  /**
   * @var ihrname\SimpleTemplateEngine Template engines to render output
   */
  private $template;
  private $fileService;
  
  /**
   * @param ihrname\SimpleTemplateEngine
   */
  public function __construct(\Twig_Environment $template, FileService $fileService)
  {
  	 $this->fileService = $fileService;
     $this->template = $template;
  }

  public function showUpload() {
    echo $this->template->render("upload.html.twig");
  }
  
  

  public function upload($data) {
  	$fileName = $data['fileToUpload']['name'];
  	$fileType = $data['fileToUpload']['type'];
  	$fileSize = $data['fileToUpload']['size'];
  	$fileTmpName = $data['fileToUpload']['tmp_name'];
  	
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
