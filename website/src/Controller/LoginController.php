<?php

namespace eifachoeppis\Controller;

use eifachoeppis\SimpleTemplateEngine;
use eifachoeppis\Service\LoginService;
use eifachoeppis\Session;

class LoginController 
{
  /**
   * @var ihrname\SimpleTemplateEngine Template engines to render output
   */
  private $template;
  private $loginService;
  private $session;
  
  /**
   * @param ihrname\SimpleTemplateEngine
   * @param \PDO
   */
  public function __construct(\Twig_Environment $template, LoginService $LoginService, Session $session)
  {
     $this->template = $template;
     $this->loginService = $LoginService;
     $this->session = $session;
  }
  
  public function showLogin(){
  	session_regenerate_id();
  	echo $this->template->render("login.html.twig");
  }
  
  public function logout($csrf){
  	if ($_SESSION["csrf"] == $csrf){
  		unset($_SESSION["email"]);
  		echo $this->template->render("logout.html.twig");
  	}
  }
  
  public function login(array $data){
  	if (!array_key_exists("email", $data) OR !array_key_exists("password", $data)){
  		$this->showLogin();
  		return;
  	}
  	
  	if($this->loginService->authenticate($data["email"], $data["password"])){
  		session_regenerate_id();
  		$_SESSION["email"] = $data["email"];
  		header("Location: /");
  	}else{
  		echo $this->template->render("login.html.twig", ["email" => $data["email"]]);
  	}
  }  
}
