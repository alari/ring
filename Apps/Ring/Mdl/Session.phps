<?php
class R_Mdl_Session extends O_Acl_Session {

	/**
	 * Returns true if current user is specified identity holder, false elsewhere
	 *
	 * @param string $identity
	 * @return bool
	 */
	static public function isIdentity( $identity )
	{
		return self::isLogged() && self::getUser()->identity == $identity;
	}

	static public function getIdentity()
	{
		if (!self::isLogged())
			return null;
		return self::getUser()->identity;
	}

}