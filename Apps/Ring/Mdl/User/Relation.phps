<?php
/**
 * @table user_relation
 *
 * @field site -has one R_Mdl_Site -inverse usr_related
 * @field author -has one R_Mdl_User -inverse usr_related
 * @field system -has one R_Mdl_Site_System -inverse usr_related
 *
 * @field flags INT(64) NOT NULL DEFAULT 0
 * @field status TINYTEXT
 *
 * @field user -has one R_Mdl_User -inverse relations
 *
 * @index site,author,system,user -unique
 * @index flags
 */
class R_Mdl_User_Relation extends O_Dao_ActiveRecord {
	const FLAG_FRIEND = 1;

	const FLAG_BANNED = 4;
	const FLAG_ADMIN = 8;
	const FLAG_MEMBER = 16;
	const FLAG_OWNER = 32;

	public function __construct( R_Mdl_User $user, O_Dao_ActiveRecord $object, $flags )
	{
		$this->user = $user;
		if ($object instanceof R_Mdl_Site_System) {
			$this->system = $object;
			if ($object->site->owner)
				$this->author = $object->site->owner;
		} elseif ($object instanceof R_Mdl_Site) {
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
		// Add a relation
		$rel = self::getRelation( $user, $object );
		if (!$rel) {
			$rel = new self( $user, $object, self::FLAG_FRIEND );
		} elseif ($rel[ "flags" ] & self::FLAG_FRIEND == 0) {
			$rel[ "flags" ] = $rel[ "flags" ] ^ self::FLAG_FRIEND;
			$rel->save();
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
		$rel = self::getRelation( $user, $object );

		if (!$rel || $rel->flags & self::FLAG_FRIEND == 0)
			return;
		if ($rel->flags & ~self::FLAG_FRIEND > 0) {
			// Remove friend flag
			$rel->flags = $rel->flags & ~self::FLAG_FRIEND;
			$rel->save();
		} else {
			// Delete relation
			$rel->delete();
		}
	}

	/**
	 * Returns relation by its holders -- user and object
	 *
	 * @param R_Mdl_User $user
	 * @param O_Dao_ActiveRecord $object
	 * @return R_Mdl_User_Relation
	 */
	static public function getRelation( $user, $object )
	{
		$q = $object->usr_related->test( "user", $user );
		if($object instanceof R_Mdl_Site) $q->test("system", 0);
		if($object instanceof R_Mdl_User) $q->test("system", 0);
		return $q->getOne();
	}
}