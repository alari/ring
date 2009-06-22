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
	const FLAG_OWNER = 32;

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
		if (!$rel) {
			$rel = new self( $user, $object, self::FLAG_FRIEND );
		}
		// Friend friends are available only if $object is user
		if (!$object instanceof R_Mdl_User)
			return;
			// Find object friends
		$friends = $object->friends;
		foreach ($friends as $friend) {
			$r = self::getRelation( $user, $friend );
			if (!$r) {
				new self( $user, $friend, self::FLAG_FRIEND_FRIEND );
			} else {
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
		// Friends friends are available only for users
		if (!$object instanceof R_Mdl_User)
			return;

		// Remove FRIEND_FRIEND flags for relations got this flag by current
		$tbl = O_Dao_TableInfo::get( __CLASS__ )->getTableName();
		$q = O_Dao_Query::get( __CLASS__, "u_rel" )->field( "u_rel.*" );
		// It's connected with this object and other user
		$q->test( "u_rel.author", $object )->test( "u_rel.user", $user, "!=" );
		// It has friend friend flag
		$q->where( "u_rel.flags & " . self::FLAG_FRIEND_FRIEND );
		// It has current user in friends
		$q->where(
				"EXISTS (SELECT * FROM $tbl ru WHERE ru.user=u_rel.user AND ru.author=? AND ru.flags & 1)",
				$user );
		// It has no other friends between it and object
		$q->where(
				"NOT EXISTS (SELECT * FROM $tbl r0 CROSS JOIN $tbl r1 ON r1.user=r0.author WHERE r0.user=u_rel.user AND r1.author=u_rel.author AND r0.flags & 1 AND r1.flags & 1 AND r1.user != ?)",
				$user );
		foreach ($q as $r) {
			if ($r[ "flags" ] == self::FLAG_FRIEND_FRIEND) {
				$r->delete();
				continue;
			}
			$r[ "flags" ] = $r[ "flags" ] & ~self::FLAG_FRIEND_FRIEND;
			$r->save();
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
		return $object->usr_related->test( "user", $user )->getOne();
	}

}