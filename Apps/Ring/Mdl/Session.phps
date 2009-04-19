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

	/**
	 * Returns current user identity
	 *
	 * @return string
	 */
	static public function getIdentity()
	{
		if (!self::isLogged())
			return null;
		return self::getUser()->identity;
	}

	/**
	 * Sets available accesses for query in current site context
	 *
	 * @param O_Dao_Query $query
	 * @param R_Mdl_Site $site
	 * @return O_Dao_Query
	 */
	static public function setQueryAccesses(O_Dao_Query $query, R_Mdl_Site $site) {
		$accesses = Array ();
			foreach (array_keys( R_Mdl_Site_System::getAccesses() ) as $acc) {
				if (self::can( "read " . $acc, $site ))
					$accesses[] = $acc;
			}
		return $query->test("access", count($accesses) ? $accesses : 0);
	}

}