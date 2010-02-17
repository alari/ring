<?php
/**
 * @field owner -has one _User -inverse resourses
 *
 * @field groups INT(16) DEFAULT 1
 * @field groups_access INT(8) DEFAULT 0
 * @field logged_access INT(8) DEFAULT 0
 * @field anonymous_access INT(1) DEFAULT 0
 * @field show_to_followers INT(1) DEFAULT 0
 *
 * @index root,show_to_followers
 * @index root,show_to_followers,owner,groups,groups_access,logged_access
 * @index anonymous_access
 * @index root,groups,groups_access
 */
abstract class R_Mdl_Resourse_Acl extends O_Dao_NestedSet_Both implements O_Acl_iResourse {

	const ACTION_READ = 1;
	const ACTION_WRITE = 2;
	const ACTION_DELETE = 4;
	const ACTION_COMMENT = 8;
	const ACTION_ADMIN = 16;

	/**
	 * Returns posts for followed sites
	 * No sortings applied!
	 *
	 * @param R_Mdl_User $user
	 * @return O_Dao_Query
	 */
	static public function getUserFollowed(R_Mdl_User $user) {
		$q = static::getQuery();
		$res = static::getTableInfo()->getTableName();
		$rel = R_Mdl_User_Relation::getTableInfo()->getTableName();
		$usr = $user->id;
		$q->join($rel, "$rel.user=".$user->id." AND $rel.site=$res.root");
		$q->where("
			$rel.flags & 3 = 1 AND
			$res.show_to_followers = 1 AND (
				$res.owner=$usr
				OR ($res.groups & $rel.groups > 0 AND $res.groups_access & 1 = 1)
				OR ($res.logged_access & 1 = 1 AND NOT ($res.groups & $rel.groups = 1 AND $res.groups_access & 1 = 0))
			)
		");
		return $q;
	}

	/**
	 * Returns posts for anonymous access
	 *
	 * To get posts for one site, use ->test("root", $site)
	 * The same thing for user
	 * No sortings applied!
	 *
	 * @return O_Dao_Query
	 */
	static public function getAnonymousAccessed() {
		$q = static::getQuery();
		$q->test("show_to_followers", 1)->test("anonymous_access", 1);
		return $q;
	}

	/**
	 * Adds access checks to query in current resourse context
	 *
	 * @param O_Dao_Query $q
	 * @return O_Dao_Query
	 */
	public function addQueryAccessChecks(O_Dao_Query $q) {
		if(R_Mdl_Session::isLogged()) {
			$rel = R_Mdl_Session::getUser()->getSiteRelation($this->root);
			$q->where("owner=? OR (groups & ? > 0 AND groups_access & 1 = 1)", R_Mdl_Session::getUser(), $rel->groups);
		} else {
			$q->test("anonymous_access", 1);
		}
		return $q;
	}

	/**
	 * Returns groups with special accesses
	 *
	 * @return O_Dao_Query
	 */
	public function getGroups() {
		return $this->root->groups->test("flag", $this->groups, "&");
	}

	/**
	 * Adds group into access list
	 *
	 * @param R_Mdl_User_Group $group
	 */
	public function addGroup(R_Mdl_User_Group $group) {
		$this->groups = ($this->groups | $group->flag) | R_Mdl_User_Group::FLAG_ADMIN;
	}

	/**
	 * Removes group from access list
	 *
	 * @param R_Mdl_User_Group $group
	 */
	public function removeGroup(R_Mdl_User_Group $group) {
		$this->groups = ($this->groups &~ $group->flags) | R_Mdl_User_Group::FLAG_ADMIN;
	}

	/**
	 * Allows $action for groups
	 *
	 * @param const $action
	 */
	public function groupsAllow($action) {
		$this->groups_access = $this->groups_access | $action;
	}

	/**
	 * Denies $action for groups
	 *
	 * @param const $action
	 */
	public function groupsDeny($action) {
		$this->groups_access = $this->groups_access &~ $action;
	}

	/**
	 * Tests groups access for $action
	 *
	 * @param const $action
	 * @return bool
	 */
	public function groupsCan($action) {
		return $this->groups_access & $action;
	}

	/**
	 * Allows $action for logged users
	 *
	 * @param const $action
	 */
	public function loggedAllow($action) {
		$this->logged_access = $this->logged_access | $action;
	}

	/**
	 * Denies $action for logged users
	 *
	 * @param const $action
	 */
	public function loggedDeny($action) {
		$this->logged_access = $this->logged_access &~ $action;
	}

	/**
	 * Tests allows for logged users
	 *
	 * @param const $action
	 * @return bool
	 */
	public function loggedCan($action) {
		return $this->logged_access & $action;
	}

	/**
	 * Allows $action for not-logged users
	 *
	 * @param const $action
	 */
	public function allowAnonymous($action) {
		$this->anonymous_access = $this->anonymous_access | $action;
	}

	/**
	 * Denies $action for not-logged users
	 *
	 * @param const $action
	 */
	public function denyAnonymous($action) {
		$this->anonymous_access = $this->anonymous_access &~ $action;
	}

	/**
	 * Returns true if user can do $action without being logged
	 *
	 * @param const $action
	 * @return bool
	 */
	public function anonymousCan($action) {
		return $this->anonymous_access & $action;
	}

	/**
	 * Acl delegate pattern
	 *
	 * @param string $action
	 * @param R_Mdl_User $user
	 * @return bool
	 */
	public function aclUserCan($action, R_Mdl_User $user=null) {
		if(strpos($action, "read")===0) {
			$action = self::ACTION_READ;
		} elseif(strpos($action, "write")===0){
			$action = self::ACTION_WRITE;
		} elseif(strpos($action, "comment")===0){
			$action = self::ACTION_COMMENT;
		} elseif(strpos($action, "delete")===0){
			$action = self::ACTION_DELETE;
		} else {
			$action = self::ACTION_ADMIN;
		}
		if(!$user) {
			return $this->anonymousCan($action);
		}
		if($user == $this->owner) {
			return true;
		}
		if($this->site->getGroupByFlag(R_Mdl_User_Group::FLAG_ADMIN)->hasUser($user)) {
			return true;
		}
		$rel = $this->site->getUserRelation($user);
		if($rel->flags & R_Mdl_User_Relation::FLAG_BAN) {
			return false;
		}
		if($rel->groups & $this->groups) {
			return $this->groupsCan($action);
		}
		return $this->loggedCan($action);
	}
}