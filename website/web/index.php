<?php

error_reporting(E_ALL);

require_once("../vendor/autoload.php");
$config = parse_ini_file(__DIR__. "/../config.ini", true);
$factory = new eifachoeppis\Factory($config);

function isAuthorized($factory){
	return $factory->getSession()->has("user");
}

switch($_SERVER["REQUEST_URI"]) {
	case "/":
		$factory->getIndexController()->homepage();
		break;
	case "/login":
		$ctr = $factory->getLoginController();
		if ($_SERVER["REQUEST_METHOD"] == "GET"){
			$ctr->showLogin();
		}else{
			$ctr->login($_POST);
		}
		break;
	case "/register":
		$ctr = $factory->getRegisterController();
		if ($_SERVER["REQUEST_METHOD"] == "GET"){
			$ctr->showRegister();
		}else{
			$ctr->register($_POST);
		}
		break;
	case "/upload":
		if (isAuthorized($factory)){
			$ctr = $factory->getFileController();
			if ($_SERVER["REQUEST_METHOD"] == "GET"){
				$ctr->showUpload();
			}else{
				$ctr->upload($_FILES, $_POST);
			}
		}else{
			header("Location: /login");
		}
		break;
	case "/reset":
		$ctr = $factory->getRegisterController();
		if ($_SERVER["REQUEST_METHOD"] == "GET"){
			$ctr->showResetRequest();
		}else{
			$ctr->createRequest($_POST);
		}
		break;
	case "/images":
		if (isAuthorized($factory)){
			$factory->getFileController()->showFiles();
		}else{
			header("Location: /login");
		}
		break;
	case "/logout":
		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			$factory->getLoginController()->logout($_POST);
		}
		else{
			header("Location: /");
		}
		break;
	default:
		$matches = [];
		if(preg_match("|^/hello/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			$factory->getIndexController()->greet($matches[1]);
			break;
		}
		if(preg_match("|^/showImage/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			if (isAuthorized($factory)){
				$factory->getFileController()->getImage($matches[1]);
			}
			break;
		}
		if(preg_match("|^/reset/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			$ctr = $factory->getRegisterController();
			if ($_SERVER["REQUEST_METHOD"] == "GET"){
				$ctr->showPasswordReset($matches[1]);
			}else{
				$ctr->resetPassword($_POST);
			}
			break;
		}
		if(preg_match("|^/activate/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			$factory->getRegisterController()->activate($matches[1]);
			break;
		}		
		header("Location: /");
}

