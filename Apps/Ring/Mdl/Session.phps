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
	 * Suiteble for resources
	 *
	 * @param O_Dao_Query $query
	 * @param R_Mdl_Site $site
	 * @return O_Dao_Query
	 */
	static public function setQueryAccesses( O_Dao_Query $q, R_Mdl_Site $site )
	{
		if(self::isLogged()) {
			if(self::getUser() == $site->owner) {
				return $q;
			}
			$rel = self::getUser()->getSiteRelation($site);
			$tbl = O_Dao_TableInfo::get($q->getClass())->getTableName();
			$q->where("$tbl.owner=?
			 OR	(groups & ? > 0 AND groups_access & 1 = 1)
			 OR (logged_access & 1 = 1 AND NOT (groups & ? = 1 AND groups_access & 1 = 0))", self::getUser(), $rel->groups, $rel->groups);
		} else {
			$q->test("anonymous_access", 1);
		}
		return $q;
	}

}