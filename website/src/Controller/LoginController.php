<?php

namespace eifachoeppis\Controller;

use eifachoeppis\SimpleTemplateEngine;
use eifachoeppis\Service\LoginService;

class LoginController 
{
  /**
   * @var ihrname\SimpleTemplateEngine Template engines to render output
   */
  private $template;
  private $loginService;
  
  /**
   * @param ihrname\SimpleTemplateEngine
   * @param \PDO
   */
  public function __construct(\Twig_Environment $template, LoginService $LoginService)
  {
     $this->template = $template;
     $this->loginService = $LoginService;
  }
  
  public function showLogin(){
  	echo $this->template->render("login.html.twig");
  }
  
  public function logout($csrf){
  	echo $this->template->render("logout.html.twig");
  }
  
  public function login(array $data){
  	if (!array_key_exists("email", $data) OR !array_key_exists("password", $data)){
  		$this->showLogin();
  		return;
  	}
  	
  	if($this->loginService->authenticate($data["email"], $data["password"])){
  		session_destroy();
  		session_start();
  		$_SESSION["email"] = $data["email"];
  		header("Location: /");
  	}else{
  		echo $this->template->render("login.html.twig", ["email" => $data["email"]]);
  	}
  }  
}
