<?php
/**
 * @table anonces
 *
 * @field site -has one R_Mdl_Site -inverse anonces -preload
 * @field owner -has one R_Mdl_User -inverse anonces -preload -show
 *
 * @field crossposts -owns many R_Mdl_Site_Crosspost -inverse anonce
 *
 * @field creative -one-of blog_post; im_picture; libro_text; sound_track; afisha_bill
 * @field blog_post -owns one R_Mdl_Sys_Blog_Post -inverse anonce
 * @field im_picture -owns one R_Mdl_Sys_Im_Picture -inverse anonce
 * @field libro_text -owns one R_Mdl_Sys_Libro_Text -inverse anonce
 * @field sound_track -owns one R_Mdl_Sys_Sound_Track -inverse anonce
 * @field afisha_bill -owns one R_Mdl_Sys_Afisha_Bill -inverse anonce
 *
 * @field collection -has one R_Mdl_Site_Collection -inverse anonces
 * @field position INT DEFAULT 0
 *
 * @field system -has one R_Mdl_Sys_Instance -inverse anonces -preload
 * @field tags -has many R_Mdl_Site_Tag -inverse anonces
 *
 * @field linked -has many R_Mdl_Site_Anonce -inverse linked
 * @field in_favorites -has many R_Mdl_User -inverse favorites
 *
 * @field access ENUM('public','protected','private','disable') NOT NULL DEFAULT 'disable' -enum public: Всем; protected: Авторизованным; private: Друзьям; disable: Только себе
 * @field time INT -show date
 * @field title VARCHAR(255) -show linkInContainer
 * @field description TEXT -show
 *
 * @index time
 * @index system,time
 * @index collection,position
 * @index position
 */
class R_Mdl_Site_Anonce extends O_Dao_NestedSet_Root {
	const NODES_CLASS = "R_Mdl_Site_Comment";

	private $_updateCollectionPosition = 0;

	public function __construct( R_Mdl_Sys_Creative $creative, R_Mdl_Sys_Implementation $instance )
	{
		parent::__construct();
		$this->system = $instance->system;
		$this->site = $instance->system->site;
		$this->save();
		$this->creative = $creative;
		$this->time = $creative->time;
		$this->access = $instance->system->access;
		$this->owner = R_Mdl_Session::getUser();
		$this->save();
		$this->creative->save();
	}

	/**
	 * Returns url of main content page
	 *
	 * @return string
	 */
	public function url()
	{
		$field = O_Dao_TableInfo::get( __CLASS__ )->getFieldInfo( "creative" )->getRealField(
				$this );
		return $this->system->creativeUrl( $this[ $field ] );
	}

	/**
	 * Simple link for creative -- without author
	 *
	 * @return string
	 */
	public function link()
	{
		return "<a href=\"" . $this->url() . "\">" . $this->title . "</a>";
	}

	/**
	 * Checks if current user can see this
	 *
	 * @return bool
	 */
	public function isVisible()
	{
		return R_Mdl_Session::can( "read " . $this->system[ "access" ], $this->site ) && R_Mdl_Session::can(
				"read " . $this[ "access" ], $this->site );
	}

	/**
	 * Returns directory to store files attached with this anonce in
	 *
	 * @return string
	 */
	public function getFilesDir()
	{
		$dir = $this->site->staticPath( "f" );
		if (!is_dir( $dir ))
			mkdir( $dir );
		$dir .= "/" . substr( $this->id, 0, 1 );
		if (!is_dir( $dir ))
			mkdir( $dir );
		$dir .= "/" . substr( $this->id, 1, 2 );
		if (substr( $dir, -1 ) == "/")
			$dir .= "x";
		if (!is_dir( $dir ))
			mkdir( $dir );
		$dir .= "/";
		return $dir;
	}

	/**
	 * Returns url base to get urls to files attached with anonce
	 *
	 * @return string
	 */
	public function getFilesUrl()
	{
		$dir = $this->site->staticUrl( "f" );
		$dir .= "/" . substr( $this->id, 0, 1 );
		$dir .= "/" . substr( $this->id, 1, 2 );
		if (substr( $dir, -1 ) == "/")
			$dir .= "x";
		$dir .= "/";
		return $dir;
	}

	/**
	 * Sets access conditions to anonces query
	 *
	 * @param O_Dao_Query $q
	 */
	static public function setQueryAccesses( O_Dao_Query $q, R_Mdl_User $user = null )
	{
		if (!$user && !R_Mdl_Session::isLogged()) {
			$q->test( "access", "public" );
			return;
		}
		$tbl = O_Dao_TableInfo::get( __CLASS__ )->getTableName();
		$r_tbl = O_Dao_TableInfo::get( "R_Mdl_User_Relation" )->getTableName();
		if (!$user)
			$user = R_Mdl_Session::getUser();
		$q->where(
				"access='public'
			OR owner=?
			OR (
				(access='protected' OR access='private')
					AND (
						(
						$tbl.owner=$r_tbl.author
						AND EXISTS (SELECT r1.author FROM $r_tbl r1 WHERE r1.flags & 1 AND r1.user=$tbl.owner AND r1.author=?)
						) OR (
						$tbl.owner!=$r_tbl.author
						)
					)
			)", $user, $user );
	}

	static public function getByUserRelations( $user )
	{
		$q = $user->{"relations.site.anonces"}->where("__rel1.flags & ".R_Mdl_User_Relation::FLAG_WATCH);
		$q->where("access='public' OR access='protected'
			OR anonce.owner=?
			OR (access='private' AND __rel1.flags & ?)
			OR (access='disable' AND __rel1.flags & ?)",
			$user, R_Mdl_User_Relation::FLAGS_PRIVATE, R_Mdl_User_Relation::FLAGS_DISABLE);
		return $q;
	}

	/**
	 * Deletes anonce
	 *
	 */
	public function delete()
	{
		if ($this->collection) {
			$this->collection->anonces->test( "position", $this->position, ">" )->field(
					"position", "position-1", 1 )->update();
		}
		parent::delete();
	}

	public function save()
	{
		parent::save();
		// Check validity of position in the cycle
		if (!$this->_updateCollectionPosition && $this->collection) {
			// If there's more then one anonce with current position
			if ($this->collection->anonces->test( "position", $this->position )->test(
					"id", $this->id, "!=" )->getOne()) {
				// Set for number of anonces -- making this last anonce
				$this->position = count( $this->collection->anonces ) + 1;
				parent::save();
				// There is still error in position -- update all positions in collection
				if ($this->collection->anonces->test( "position",
						$this->position )->test( "id", $this->id, "!=" )->getOne()) {
					$i = 0;
					foreach ($this->collection->anonces as $a) {
						$a->_updateCollectionPosition = 1;
						$a->position = ++$i;
						$a->save();
						$a->_updateCollectionPosition = 0;
					}
				}
			}
		}
	}

	/**
	 * Sets anonce position in collection
	 *
	 * @param int $newPosition
	 */
	public function setPosition( $newPosition )
	{
		if ($newPosition == $this->position)
			return;
		if ($newPosition <= 0 || $newPosition > count( $this->collection->anonces ) + 1)
			return;
			/* @var $anonces O_Dao_Query */
		$anonces = $this->collection->anonces;

		if ($newPosition > $this->position) {
			$anonces->test( "position", $this->position, ">" )->test( "position", $newPosition,
					"<=" )->field( "position", "position-1", 1 )->update();
		} else {
			$anonces->test( "position", $this->position, "<" )->test( "position", $newPosition,
					">=" )->field( "position", "position+1", 1 )->update();
		}

		$this->position = $newPosition;
		parent::save();
	}

	public function getNext()
	{
		return $this->getNextOrPrev( 0 );
	}

	public function getPrevious()
	{
		return $this->getNextOrPrev( 1 );
	}

	private function getNextOrPrev( $prev )
	{
		if ($prev) {
			$op_test = "<";
			$op_ord_pos = " DESC";
		} else {
			$op_test = ">";
			$op_ord_pos = "";
		}
		$anonce = null;
		if ($this->collection) {
			$coll = $this->collection->anonces->test( "position", $this->position, $op_test )->clearOrders()->orderBy(
					"position" . $op_ord_pos );
			//self::setQueryAccesses( $coll );
			$anonce = $coll->getOne();
			if ($anonce)
				return $anonce;
			$coll = $this->system->collections->test( "position", $this->collection->position,
					$op_test )->clearOrders()->orderBy( "position" . $op_ord_pos )->getOne();
			if ($coll) {
				$coll = $coll->anonces->clearOrders()->orderBy( "position" . $op_ord_pos );
				//self::setQueryAccesses( $coll );
				$anonce = $coll->getOne();
				if ($anonce)
					return $anonce;
			}
			return null;
		}
		$q = $this->system->anonces->test( "time", $this->time, $op_test )->orderBy(
				"time" . $op_ord_pos );
		//self::setQueryAccesses( $q );
		return $q->getOne();
	}

}