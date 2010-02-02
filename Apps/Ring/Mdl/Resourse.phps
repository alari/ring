<?php
/**
 * @table units
 *site 23
 * @field url_part VARCHAR(12) NOT NULL
 * @field url_cache VARCHAR(255) NOT NULL
 *
 * @field title VARCHAR(255)
 *
 * @field type TINYINT NOT NULL DEFAULT 0
 * @field content INT
 *
 * @field groups INT(16) DEFAULT 0
 * @field groups_access INT(8) DEFAULT 0
 * @field logged_access INT(8) DEFAULT 0
 * @field anonymous_access INT(1) DEFAULT 0
 * @field show_in_friendlist INT(1) DEFAULT 0
 *
 * @index root,url_cache(24)
 */
class R_Mdl_Resourse extends O_Dao_NestedSet_Both {
	const ROOT_CLASS = "R_Mdl_Site";
	const NODES_CLASS = "R_Mdl_Comment";

	const ALLOW_READ = 1;
	const ALLOW_WRITE = 2;
	const ALLOW_DELETE = 4;
	const ALLOW_COMMENT = 8;
	const ALLOW_ADMIN = 16;

	static private $types = Array(0=>"Folder", 1=>"Text");

	public function getGroups() {
		return $this->root->groups->test("flags", $this->groups, "&");
	}

	public function addGroup(R_Mdl_User_Group $group) {
		$this->groups = $this->groups | $group->flags;
	}

	public function removeGroup(R_Mdl_User_Group $group) {
		$this->groups = $this->groups &~ $group->flags;
	}

	public function groupsAllow($action) {
		$this->groups_access = $this->groups_access | $action;
	}

	public function groupsDeny($action) {
		$this->groups_access = $this->groups_access &~ $action;
	}

	public function groupsCan($action) {
		return $this->groups_access & $action;
	}

	public function loggedAllow($action) {
		$this->logged_access = $this->logged_access | $action;
	}

	public function loggedDeny($action) {
		$this->logged_access = $this->logged_access &~ $action;
	}

	public function loggedCan($action) {
		return $this->logged_access & $action;
	}

	public function allowAnonymousRead() {
		$this->anonymous_access = self::ALLOW_READ;
	}

	public function denyAnonymousRead() {
		$this->anonymous_access = ~self::ALLOW_READ;;
	}

	public function anonymousCan($action) {
		return $this->anonymous_access & $action;
	}
}