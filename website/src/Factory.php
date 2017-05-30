<?php
	namespace eifachoeppis;
	
	class Factory{
		
		private $config;
		private $session;		
		
		public function __construct(array $config){
			$this->config = $config;
		}
		
		public function getIndexController(){
			return new Controller\IndexController($this->getTwigEngine());
		}
		
		public function getLoginController(){

			return new Controller\LoginController($this->getTwigEngine(), $this->getLoginService(), $this->getSession());
		}
		
		public function getSession(){
			if(!$this->session){
				$this->session = new Session();
			}
			return $this->session;
		}
		
		public function getRegisterController(){
			return new Controller\RegisterController($this->getTwigEngine(), $this->getRegisterService(), $this->getMailer());
		}
		
		public function getFileController(){
			return new Controller\FileController($this->getTwigEngine(), $this->getFileService(), $this->getSession());
		}
		
		public function getTemplateEngine(){
			return new SimpleTemplateEngine(__DIR__ . "/../templates/");
		}
		
		public function getTwigEngine(){
			$loader = new \Twig_Loader_Filesystem(__DIR__ . "/../templates/");
			$twig = new \Twig_Environment($loader);
			$twig->addGlobal("_SESSION", $this->getSession());
			return $twig;
		}
		
		public function getMailer(){
			return \Swift_Mailer::newInstance(
					\Swift_SmtpTransport::newInstance(
							$this->config["email"]["host"],
							$this->config["email"]["port"],
							$this->config["email"]["security"])
					->setUsername($this->config["email"]["user"])
					->setPassword($this->config["email"]["password"])
					);
		}
		
		public function getPdo(){
			return new \PDO(
					"mysql:host=". $this->config["database"]["host"] .";dbname=app;charset=utf8",
					$this->config["database"]["user"],
					$this->config["database"]["password"],
					[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
					);
		}
		
		public function getLoginService(){
			return new Service\LoginMySqlService($this->getPdo());
		}
		
		public function getRegisterService(){
			return new Service\RegisterMySqlService($this->getPdo());	
		}
		
		public function getFileService(){
			return new Service\FileMySqlService($this->getPdo());
		}
	}
