<?php

error_reporting(E_ALL);
session_start();

require_once("../vendor/autoload.php");
$config = parse_ini_file(__DIR__. "/../config.ini", true);
$factory = new eifachoeppis\Factory($config);

switch($_SERVER["REQUEST_URI"]) {
	case "/":
		$factory->getIndexController()->homepage();
		$factory->getMailer()->send(
				Swift_Message::newInstance("Title")
				->setFrom(["gibz.module.151@gmail.com" => "M151 Website"])
				->setTo(["hamburg56@hotmail.com" => "hi"])
				->setBody("Here is the message itself")
				);
		break;
	case "/login":
		$ctr = $factory->getLoginController();
		if ($_SERVER["REQUEST_METHOD"] == "GET"){
			$ctr->showLogin();
		}else{
			$ctr->login($_POST);
		}
		break;
	default:
		$matches = [];
		if(preg_match("|^/hello/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			$factory->getIndexController()->greet($matches[1]);
			break;
		}
		echo "Not Found";
}

