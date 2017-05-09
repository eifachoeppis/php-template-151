<?php

namespace eifachoeppis\Controller;

class UploadController 
{
  /**
   * @var ihrname\SimpleTemplateEngine Template engines to render output
   */
  private $template;
  
  /**
   * @param ihrname\SimpleTemplateEngine
   */
  public function __construct(\Twig_Environment $template)
  {
     $this->template = $template;
  }

  public function showUpload() {
    echo $this->template->render("upload.html.twig");
  }

  public function upload() {
  	echo $this->template->render("upload.html.twig");
  }
 
}
