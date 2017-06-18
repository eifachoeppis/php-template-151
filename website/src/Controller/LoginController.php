<?php

namespace eifachoeppis\Controller;

use eifachoeppis\SimpleTemplateEngine;
use eifachoeppis\Service\LoginService;
use eifachoeppis\Session;
use eifachoeppis\Entity\UserEntity;

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
  	$this->session->regenerateId();
  	echo $this->template->render("login.html.twig");
  }
  
  public function logout($data){
  	if ($this->session->checkValue("csrf", $data["csrf"])){
  		$this->session->unset("user");
  		echo $this->template->render("logout.html.twig");
  	}
  	else{
  		header("Location: /");
  	}
  }
  
  public function login(array $data){
  	if (!array_key_exists("email", $data) OR !array_key_exists("password", $data)){
  		$this->showLogin();
  		return;
  	}
  	
  	$userEntity = new UserEntity();
  	$userEntity->setEmail($data["email"]);
  	$userEntity->setPassword($data["password"]);
  	
  	if($user = $this->loginService->authenticate($userEntity)){
  		$this->session->regenerateId();
  		$this->session->set("user", $user);
  		header("Location: /");
  	}else{
  		echo $this->template->render("login.html.twig", ["email" => $data["email"]]);
  	}
  }  
}
