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
	const FLAG_FRIEND_FRIEND = 2;
	const FLAG_BANNED = 4;
	const FLAG_ADMIN = 8;
	const FLAG_MEMBER = 16;
	const FLAG_READER = 32;

	public function __construct( R_Mdl_User $user, O_Dao_ActiveRecord $object, $flags )
	{
		$this->user = $user;
		if ($object instanceof R_Mdl_Site_System) {
			$this->system = $object;
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

	public function delete()
	{
		// Remove FRIEND_FRIEND flags
		if ($this[ "flags" ] & self::FLAG_FRIEND) {
			$object = $this->site;
			if (!$object)
				$object = $this->author;
			if (!$object)
				$object = $this->system;
			self::removeFriend( $this->user, $object );
		}
		parent::delete();
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
		if (!$rel)
			$rel = new self( $user, $object, self::FLAG_FRIEND );
			// Find friends to set FRIEND_FRIEND relations
		$friends = $user->relations->test( "flags", self::FLAG_FRIEND, "&" )->test(
				"author", 0, ">" )->getAll();
		foreach ($friends as $friend) {
			$r = self::getRelation( $friend, $object );
			if (!$r)
				new self( $friend, $object, self::FLAG_FRIEND_FRIEND );
			else {
				$r->flags = $r->flags | self::FLAG_FRIEND_FRIEND;
				$r->save();
			}
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
			// Delete relation, nullify flags to avoid loops
			$rel->flags = 0;
			$rel->save();
			$rel->delete();
		}
		// Remove FRIEND_FRIEND flags for relations got this flag by current
		$tbl = O_Dao_TableInfo::get( __CLASS__ )->getTableName();
		/* @var $q O_Dao_Query */
		// Connections with this object
		$q = $object->usr_related;
		// Theirs owners are friends of $user
		$q->test( "user", $user->relations->field( "author" ), O_Dao_Query::IN );
		// They have no other friends with $object
		$q->clearFrom()->from( $tbl, "usr_rel" );
		$q->where( "NOT EXISTS ?",
				$object->usr_related->test( "user", $user, "!=" )->where( "author=usr_rel.user" ) );
		// If there's only FRIEND_FRIEND flag, delete relation
		$q1 = clone $q;
		$q1->test( "flags", self::FLAG_FRIEND_FRIEND )->delete();
		// Else remove flag
		$q->field( "flags", "flags &~ " . self::FLAG_FRIEND_FRIEND, true )->update();
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
		return $object->usr_related->test( "user", $user )->getOne();
	}

}