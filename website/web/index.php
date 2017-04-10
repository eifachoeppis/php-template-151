<?php

use eifachoeppis\Controller\LoginController;
use eifachoeppis\Service\LoginMySqlService;

error_reporting(E_ALL);
session_start();

require_once("../vendor/autoload.php");
$factory = new eifachoeppis\Factory();
$tmpl = $factory->getTemplateEngine();
$pdo = $factory->getPdo();
$loginService = $factory->getLoginService();

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
	default:
		$matches = [];
		if(preg_match("|^/hello/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			$factory->getIndexController()->greet($matches[1]);
			break;
		}
		echo "Not Found";
}

