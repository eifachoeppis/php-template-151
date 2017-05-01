<?php

namespace eifachoeppis\Controller;

use eifachoeppis\SimpleTemplateEngine;

class IndexController 
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

  public function homepage() {
    echo $this->template->render("index.html.php");
  }

  public function greet($name) {
  	echo $this->template->render("hello.html.php", ["name" => $name]);
  }
 
}
