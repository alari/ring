<?php
/**
 * @table user_relation
 *
 * @field site -has one R_Mdl_Site -inverse usr_related
 * @field author -has one R_Mdl_User -inverse usr_related
 *
 * @field flags INT(64) NOT NULL DEFAULT 0
 * @field status TINYTEXT
 *
 * @field user -has one R_Mdl_User -inverse relations
 *
 * @index site,author,user -unique
 * @index flags
 */
class R_Mdl_User_Relation extends O_Dao_ActiveRecord {
	const FLAG_WATCH = 1;
	const FLAG_IS_FRIEND = 2;
	const FLAG_IS_BANNED = 4;
	const FLAG_IS_ADMIN = 8;
	const FLAG_IS_MEMBER = 16;
	const FLAG_IS_LEADER = 32;

	const FLAGS_PRIVATE = 58;
	const FLAGS_DISABLE = 40;

	public function __construct( R_Mdl_User $user, O_Dao_ActiveRecord $object, $flags )
	{
		$this->user = $user;
		if ($object instanceof R_Mdl_Site) {
			$this->site = $object;
			if ($object->owner)
				$this->author = $object->owner;
		} elseif ($object instanceof R_Mdl_User) {
			$this->author = $object;
			if ($object->site)
				$this->site = $object->site;
		}
		$this->flags = $flags;
		parent::__construct();
	}

	/**
	 * User adds something to friends. Something is in friends of user.
	 *
	 * @param R_Mdl_User $user
	 * @param O_Dao_ActiveRecord $object
	 */
	static public function addFriend( $user, $object )
	{
		// Add a watch relation for target site
		if($object instanceof R_Mdl_Site || $object->site instanceof R_Mdl_Site){
			self::getRelation( $user, $object, self::FLAG_WATCH );
		}
		// Add "my_friend" relation
		if($object instanceof R_Mdl_User || $object->owner instanceof R_Mdl_User) {
			$author = $object instanceof R_Mdl_User ? $object : $object->owner;
			self::getRelation($author, $user, self::FLAG_IS_FRIEND);
		}
	}

	/**
	 * User has no longer object in his friends. Object is no longer friended by user.
	 *
	 * @param R_Mdl_User $user
	 * @param O_Dao_ActiveRecord $object
	 */
	static public function removeFriend( $user, $object )
	{
		// Remove watch flag from direct relation
		$rel = self::getRelation( $user, $object );

		// Relation with community, or no flags in relation
		if(!$rel["flags"] || !$rel["author"]) {
			$rel->delete();
		}

		// Flag is already removed
		if (!$rel || $rel["flags"] & self::FLAG_WATCH == 0) {
			return;
		}

		// There are other flags, save them
		if ($rel["flags"] & ~self::FLAG_WATCH > 0) {
			$rel->flags = $rel->flags & ~self::FLAG_WATCH;
			$rel->save();
		}

	}

	/**
	 * Returns relation by its holders -- user and object
	 *
	 * @param R_Mdl_User $user
	 * @param O_Dao_ActiveRecord $object -- user or site
	 * @param int $createWithFlag if set, relation with this flags will be returned
	 * @return R_Mdl_User_Relation
	 */
	static public function getRelation( $user, $object, $createWithFlag = null )
	{
		$q = $object->usr_related->test( "user", $user );
		$rel = $q->getOne();
		if(!$rel && $createWithFlag !== null) {
			$rel = new self($user, $object, $createWithFlag);
		}
		if($createWithFlag !== null && $rel["flags"] & $createWithFlag == 0){
			$rel["flags"] += $createWithFlag;
			$rel->save();
		}
		return $rel;
	}
}