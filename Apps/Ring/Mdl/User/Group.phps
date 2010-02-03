<?php
/**
 * @table user_groups
 *
 * @field site -has one _Site -inverse groups
 *
 * @field flag INT(16)
 * @field title TINYTEXT
 *
 * @index type
 * @index site,flag -unique
 *
 * @tail ENGINE=MyISAM
 */
class R_Mdl_User_Group extends O_Dao_ActiveRecord {
	const FLAG_MEMBER = 2;
	const FLAG_ADMIN = 1;

	static private function getNewGroupFlag(R_Mdl_Site $site, $flag) {
		if($flag == self::FLAG_MEMBER || $flag == self::FLAG_ADMIN) {
			return $flag;
		}
		$max = $site->groups->getFunc("flag", "MAX");
		if($max < 2) $max = 2;
		if($max < (1<<15)) return $max << 1;
		return null;
	}

	static private function getNewGroupTitle($flag, $isComm) {
		switch($flag){
			case self::FLAG_MEMBER: return $isComm?"Участники":"Друзья";
			case self::FLAG_ADMIN: return $isComm?"Руководство":"Автор";
			default: return "Новая группа";
		}
	}

	public function __construct(R_Mdl_Site $site, $flag, $title=null) {
		try {
			$this->flag = self::getNewGroupFlag($site, $flag);
			$this->title = $title ? $title : self::getNewGroupTitle($flag, $site["type"]==R_Mdl_Site::TYPE_COMM);
			$this->site = $site;
			parent::__construct();
		} catch(PDOException $e) {
			return null;
		}
	}

	/**
	 * Creates groups to a new site
	 *
	 * @param R_Mdl_Site $site
	 * @param R_Mdl_User $owner
	 */
	static public function createSiteGroups(R_Mdl_Site $site, R_Mdl_User $owner) {
		$mem = new self($site, self::FLAG_MEMBER);
		$adm = new self($site, self::FLAG_ADMIN);
		$adm->addUser($owner);
		if($site["type"]==R_Mdl_Site::TYPE_AUTH) {
			$site->owner = $owner;
			$site->save();
			foreach($owner->relations->test("flags", R_Mdl_User_Relationship::FLAG_FOLLOW, "&") as $r){
				if($r->site->owner) {
					$mem->addUser($r->site->owner);
				}
			}
		}
	}

	/**
	 * Returns relation object for user and site
	 *
	 * @param R_Mdl_User $user
	 * @return R_Mdl_User_Relationship
	 */
	public function getRelation(R_Mdl_User $user) {
		return R_Mdl_User_Relationship::getRelation($user, $this->site, 0);
	}

	/**
	 * Adds user to group
	 *
	 * @param R_Mdl_User $user
	 * @return R_Mdl_User_Relationship
	 */
	public function addUser(R_Mdl_User $user) {
		$rel = $this->getRelation($user);
		$rel->addGroup($this);
		return $rel;
	}

	/**
	 * Removes user from the group
	 *
	 * @param R_Mdl_User $user
	 * @return R_Mdl_User_Relationship
	 */
	public function removeUser(R_Mdl_User $user) {
		$rel = $this->getRelation($user);
		$rel->removeGroup($this);
		return $rel;
	}

	public function hasUser(R_Mdl_User $user) {
		return $this->getRelation($user)->groups & $this->flag;
	}

	public function getUsers() {
		return $this->{"site.relations.user"}->test("groups", $this->flag, "&");
	}

	public function getRelations() {
		return $this->{"site.relations"}->test("groups", $this->flag, "&");
	}
}