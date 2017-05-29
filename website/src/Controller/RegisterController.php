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
  private $mailer;
  
  /**
   * @param ihrname\SimpleTemplateEngine
   * @param \PDO
   */
  public function __construct(\Twig_Environment $template, RegisterService $registerService, \Swift_Mailer $mailer)
  {
     $this->template = $template;
     $this->registerService = $registerService;
     $this->mailer = $mailer;
  }
  
  public function showRegister(){
  	echo $this->template->render("register.html.twig");
  }
  
  public function showPasswordReset($guid){
  	if ($this->registerService->checkPasswordReset($guid)){
  		echo $this->template->render("reset.html.twig", ["guid" => $guid]);
  	}
  	else{
  		header("Location: /");
  	}
  }
  
  public function showResetRequest(){
  	echo $this->template->render("reset.html.twig");
  }
  
  public function activate($guid){
  	$activationtext = "";
  	if ($this->registerService->activateUser($guid)){
  		$activationtext = "Your Account is now activated.";
  	}else{
  		$activationtext = "No account found to activate. The activation link expires after 1 hour.";
  	}
  	echo $this->template->render("index.html.twig", ["activation" => $activationtext]);
  }
  
  public function register(array $data){
  	if (!array_key_exists("email", $data)
  			OR !array_key_exists("password", $data)
  			OR !array_key_exists("passwordConfirm", $data)){
  		$this->showRegister();
  		return;
  	}
  	
  	
  	if($activationCode = $this->registerService->createUser($data["email"], $data["password"], $data["passwordConfirm"])){
  		$this->mailer->send(
  				\Swift_Message::newInstance("Account Activation")
  				->setFrom(["gibz.module.151@gmail.com" => "Photogallery"])
  				->setTo($data["email"])
  				->setBody(
  						'<html>' .
  						'<head></head>' .
  						' <body>' .
  						' <p>Click this link to activate your Account:</p>' .
		  				'<p><a href=https://'.
  						$_SERVER["HTTP_HOST"] .
  						'/activate/'.
  						$activationCode .
  						'></a></p>'.
		  				' </body>' .
		  				'</html>',
		  				'text/html')
  				);
  		header("Location: /");
  	}else{
  		echo $this->template->render("register.html.twig", ["email" => $data["email"]]);
  	}
  }  
}
