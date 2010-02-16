<?php
/**
 * @table user_relations
 *
 * @field site -has one _Site -inverse relations
 * @field user -has one _User -inverse relations
 *
 * @field groups INT(16) NOT NULL DEFAULT 0
 * @field flags INT(4) NOT NULL DEFAULT 0
 * @field status TINYTEXT
 *
 * @index site,user -unique
 * @index site,user,groups,flags
 * @index site,flags
 * @index site,flags,groups
 *
 * @tail ENGINE=MyISAM
 */
class R_Mdl_User_Relation extends O_Dao_ActiveRecord {
	const FLAG_FOLLOW = 1;
	const FLAG_BAN = 2;
	const FLAG_INVITE = 4;
	const FLAG_REQUEST = 8;
	const FLAG_ADMIN = 16;

	static private $objs = Array();

	/**
	 * Creates new relation
	 *
	 * @param R_Mdl_User $user
	 * @param R_Mdl_Site $site
	 * @param int $flags
	 */
	public function __construct( R_Mdl_User $user, R_Mdl_Site $site, $flags=0 )
	{
		$this->user = $user;
		$this->site = $site;
		$this->flags = $flags;

		parent::__construct();
	}

	/**
	 * Adds a group to relation
	 *
	 * @param R_Mdl_User_Group $group
	 */
	public function addGroup(R_Mdl_User_Group $group) {
		$this->groups = $this->groups | $group->flag;
		$this->save();
	}

	/**
	 * Removes group from relation
	 *
	 * @param R_Mdl_User_Group $group
	 */
	public function removeGroup(R_Mdl_User_Group $group) {
		$this->groups = $this->groups &~ $group->flag;
		$this->save();
	}

	/**
	 * Returns all groups user is in
	 *
	 * @return O_Dao_Query
	 */
	public function getGroups() {
		return $this->site->groups->test("flag", $this->groups, "&");
	}

	/**
	 * Adds flag into relation
	 *
	 * @param const $flag
	 */
	public function addFlag($flag) {
		$this->flags = $this->flags | $flag;
		$this->save();
	}

	/**
	 * Removes flag from relation
	 *
	 * @param const $flag
	 */
	public function removeFlag($flag) {
		$this->flags = $this->flags &~ $flag;
		$this->save();
	}

	/**
	 * Returns relation object between user and site. Creates it if necessary
	 *
	 * @param R_Mdl_User $user
	 * @param R_Mdl_Site $site
	 * @param const $createWithFlags
	 * @return R_Mdl_User_Relation
	 */
	static public function getRelation(R_Mdl_User $user, R_Mdl_Site $site, $createWithFlags=null) {
		$k = $user["id"].".".$site["id"];
		if(array_key_exists($k, self::$objs)) {
			return self::$objs[$k];
		}
		self::$objs[$k] = static::getQuery()->test("user", $user)->test("site", $site)->getOne();
		if(!self::$objs[$k] && $createWithFlags !== null) {
			return self::$objs[$k] = new self($user, $site, $createWithFlags);
		}
		return self::$objs[$k];
	}
}