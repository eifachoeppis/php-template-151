<?php

namespace eifachoeppis\Service;

interface RegisterService{
	/**
	 * 
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function createUser($username, $password, $passwordConfirm);
	public function activateUser($guid);
}

?>