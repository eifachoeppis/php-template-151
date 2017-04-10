<?php

namespace eifachoeppis\Service;

interface LoginService{
	/**
	 * 
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function authenticate($username, $password);
}

?>