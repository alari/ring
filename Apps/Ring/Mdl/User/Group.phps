<?php
/**
 * @table user_groups
 *
 * @field site -has one _Site -inverse groups
 *
 * @field type INT(2) DEFAULT 0
 * @field flags INT(16)
 * @field title TINYTEXT
 *
 * @index site,type
 * @index type
 * @index site,flags -unique
 *
 * @tail ENGINE=MyISAM
 */
class R_Mdl_User_Group extends O_Dao_ActiveRecord {
	const TYPE_DEFAULT = 0;
	const TYPE_MEMBER = 1;
	const TYPE_ADMIN = 3;

	static private function getNewGroupFlag(R_Mdl_Site $site, $type) {
		switch($type) {
			case self::TYPE_MEMBER:
				return 1;
			case self::TYPE_ADMIN:
				return (1<<16)-1;
			default:
				$max = $site->groups->test("type", self::TYPE_ADMIN, "!=")->getFunc("flags", "MAX");
				if($max == 0) $max = 1;
				if($max < (1<<15)) return $max << 1;
				return 0;
		}
	}

	static private function getNewGroupTitle($type, $isComm) {
		switch($type){
			case self::TYPE_MEMBER: return $isComm?"Участники":"Друзья";
			case self::TYPE_ADMIN: return "Администрация";
			default: return "Новая группа";
		}
	}

	public function __construct(R_Mdl_Site $site, $type, $title=null) {
		try {
			$this->flags = self::getNewGroupFlag($site, $type);
			$this->type = $type;
			$this->title = $title ? $title : self::getNewGroupTitle($type, $site["type"]==R_Mdl_Site::TYPE_COMM);
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
		$mem = new self($site, self::TYPE_MEMBER);
		$adm = new self($site, self::TYPE_ADMIN);
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
		if($this->type == self::TYPE_ADMIN) {
			$rel->flags = 0;
			$rel->save();
			foreach($rel->getGroups() as $g) {
				$rel->addGroup($g);
			}
		}
		return $rel;
	}

	public function getUsers() {
		return $this->{"site.relations.user"}->test("groups", $this->flags, "&");
	}

	public function getRelations() {
		return $this->{"site.relations"}->test("groups", $this->flags, "&");
	}
}