<?php

namespace eifachoeppis\Service;

use eifachoeppis\Entity\UserEntity;

interface LoginService{
	/**
	 * 
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function authenticate(UserEntity $user);
}

