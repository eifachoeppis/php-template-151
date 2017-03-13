<?php

use eifachoeppis\Controller\LoginController;

error_reporting(E_ALL);

require_once("../vendor/autoload.php");
$tmpl = new eifachoeppis\SimpleTemplateEngine(__DIR__ . "/../templates/");

switch($_SERVER["REQUEST_URI"]) {
	case "/":
		(new eifachoeppis\Controller\IndexController($tmpl))->homepage();
		break;
	case "/login":
		(new eifachoeppis\Controller\LoginController($tmpl))->showLogin();
		break;
	default:
		$matches = [];
		if(preg_match("|^/hello/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			(new eifachoeppis\Controller\IndexController($tmpl))->greet($matches[1]);
			break;
		}
		echo "Not Found";
}

