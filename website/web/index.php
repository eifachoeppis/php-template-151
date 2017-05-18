<?php

error_reporting(E_ALL);
session_start();

require_once("../vendor/autoload.php");
$config = parse_ini_file(__DIR__. "/../config.ini", true);
$factory = new eifachoeppis\Factory($config);
function isAuthorized(){
	return array_key_exists("email", $_SESSION);
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
	case "/logout":
		if (isAuthorized()){
			$factory->getLoginController()->showLogout();
		}else{
			header("Location: /login");
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
		if (isAuthorized()){
			$ctr = $factory->getFileController();
			if ($_SERVER["REQUEST_METHOD"] == "GET"){
				$ctr->showUpload();
			}else{
				$ctr->upload($_FILES);
			}
		}else{
			header("Location: /login");
		}
		break;
	case "/images":
		if (isAuthorized()){
			$factory->getFileController()->showFiles();
		}else{
			header("Location: /login");
		}
		break;
	default:
		$matches = [];
		if(preg_match("|^/hello/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			$factory->getIndexController()->greet($matches[1]);
			break;
		}
		if(preg_match("|^/showImage/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			$factory->getFileController()->getImage($matches[1]);
			break;
		}
		if(preg_match("|^/reset/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			$factory->getRegisterController()->showPasswordReset();
			break;
		}
		if(preg_match("|^/activate/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			$factory->getRegisterController()->activate($matches[1]);
			break;
		}
		header("Location: /");
}

