<?php

namespace eifachoeppis\Controller;

use eifachoeppis\Service\RegisterService;

class RegisterController 
{
  /**
   * @var ihrname\SimpleTemplateEngine Template engines to render output
   */
  private $template;
  private $registerService;
  
  /**
   * @param ihrname\SimpleTemplateEngine
   * @param \PDO
   */
  public function __construct(\Twig_Environment $template, RegisterService $registerService)
  {
     $this->template = $template;
     $this->registerService = $registerService;
  }
  
  public function showRegister(){
  	echo $this->template->render("register.html.twig");
  }
  
  public function register(array $data){
  	if (!array_key_exists("email", $data)
  			OR !array_key_exists("password", $data)
  			OR !array_key_exists("passwordConfirm", $data)){
  		$this->showRegister();
  		return;
  	}
  	
  	if($this->registerService->createUser($data["email"], $data["password"], $data["passwordConfirm"])){
  		header("Location: /");
  	}else{
  		echo $this->template->render("register.html.twig", ["email" => $data["email"]]);
  	}
  }  
}
