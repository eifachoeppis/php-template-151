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
  	
  	if(!get_magic_quotes_gpc())
  	{
  		$fileName = addslashes($fileName);
  	}
  	
  	$fp = fopen($fileTmpName, 'r');
  	$content = fread($fp, filesize($fileTmpName));
  	$content = addslashes($content);
  	fclose($fp);
  	
  	$this->fileService->saveToDatabase($fileName, $fileType, $fileSize, $content);
  	header("Location: /");
  }
  
  public function showFiles(){
  	$this->template->render("images.html.twig");
  }
 
}
