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
 * 			ACCESS MIGRATIONS
 * @field groups INT(16) DEFAULT 3
 * @field groups_access INT(8) DEFAULT 1
 * @field logged_access INT(8) DEFAULT 1
 * @field anonymous_access INT(1) DEFAULT 1
 * @field show_to_followers INT(1) DEFAULT 1
 *
 * @field access_comment ENUM('protected','private','disable') NOT NULL DEFAULT 'protected' -enum protected: Авторизованным; private: Друзьям; disable: Только себе
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
class R_Mdl_Site_Anonce extends O_Dao_NestedSet_Root implements O_Acl_iResource {
	const NODES_CLASS = "R_Mdl_Site_Comment";

	private $_updateCollectionPosition = 0;

	public function __construct(R_Mdl_Sys_Creative $creative, R_Mdl_Sys_Implementation $instance) {
		parent::__construct ();
		$this->system = $instance->system;
		$this->site = $instance->system->site;
		$this->save ();
		$this->creative = $creative;
		$this->time = $creative->time;
		$this->access = $instance->system["access"];
		$this->owner = R_Mdl_Session::getUser ();
		$this->save ();
		$this->creative->save ();

		$this->createResource();
	}

	public function createResource() {
		if($this->getResource()) return;
		$res = new R_Mdl_Resource($this->site);
		$this->system->getResource()->injectBottom($res);
		$res->reload();
		$res->type = R_Mdl_Resource::TYPE_ANONCE;
		$res->groups = 3;
		$res->show_to_followers = 1;
		$res->setContent($this);
		$this->syncRes($res);
	}

	private function syncRes(R_Mdl_Resource $res=null) {
		if(!$res) $res = $this->getResource();
		if(!$res) return;
		$sys = $this->system->getResource();

		$res->url_part = $this->creative->id;
		$res->url_cache = $sys->url_cache."/".$this->creative->id;

		$double = Array("groups","groups_access","logged_access","anonymous_access","show_to_followers","title","time");
		foreach($double as $f) $res->$f = $this->$f;

		if($this->collection) {
			$coll_res = $this->collection->getResource();
			$prev = $this->collection->anonces->test("position", $this->position, "<")->clearOrders()->orderBy("position DESC")->getOne();
			if($prev) {
				$prev_res = $prev->getResource();
				if(!$prev_res) $prev->createResource();
				$prev_res = $prev->getResource();
				$prev_res->injectAfter($res);
			}
			else $coll_res->injectTop($res);
		}

		$res->save();
	}

	/**
	 * Deletes anonce
	 *
	 */
	public function delete() {
		if ($this->collection) {
			$this->collection->anonces->test ( "position", $this->position, ">" )->field ( "position", "position-1", 1 )->update ();
		}
		$this->getResource()->delete();
		parent::delete ();
	}

	public function save() {
		// 								ACCESS MIGRATION
		$this->setMigrateAccesses();
		parent::save ();
		$this->syncRes();
		// Check validity of position in the cycle
		if (! $this->_updateCollectionPosition && $this->collection) {
			// If there's more then one anonce with current position
			if ($this->collection->anonces->test ( "position", $this->position )->test ( "id", $this->id, "!=" )->getOne ()) {
				// Set for number of anonces -- making this last anonce
				$this->position = count ( $this->collection->anonces ) + 1;
				parent::save ();
				// There is still error in position -- update all positions in collection
				if ($this->collection->anonces->test ( "position", $this->position )->test ( "id", $this->id, "!=" )->getOne ()) {
					$i = 0;
					foreach ( $this->collection->anonces as $a ) {
						$a->_updateCollectionPosition = 1;
						$a->position = ++ $i;
						$a->save ();
						$a->_updateCollectionPosition = 0;
					}
				}
			}
		}
	}

	/**
	 * @return R_Mdl_Resource
	 */
	public function getResource() {
		return $this->system->getResource()->getChilds()->test("content", $this->id)->test("content_class", __CLASS__)->getOne();
	}

	/**
	 * Returns url of main content page
	 *
	 * @return string
	 */
	public function url() {
		$field = O_Dao_TableInfo::get ( __CLASS__ )->getFieldInfo ( "creative" )->getRealField ( $this );
		return $this->system->creativeUrl ( $this [$field] );
	}

	/**
	 * Simple link for creative -- without author
	 *
	 * @return string
	 */
	public function link() {
		return "<a href=\"" . $this->url () . "\">" . $this->title . "</a>";
	}

	/**
	 * Checks if current user can see this
	 *
	 * @return bool
	 */
	public function isVisible() {
		return R_Mdl_Session::can ( "read " . $this->system ["access"], $this->site ) && R_Mdl_Session::can ( "read " . $this ["access"], $this->site );
	}

	/**
	 * Returns directory to store files attached with this anonce in
	 *
	 * @return string
	 */
	public function getFilesDir() {
		$dir = $this->site->staticPath ( "f" );
		if (! is_dir ( $dir ))
			mkdir ( $dir );
		$dir .= "/" . substr ( $this->id, 0, 1 );
		if (! is_dir ( $dir ))
			mkdir ( $dir );
		$dir .= "/" . substr ( $this->id, 1, 2 );
		if (substr ( $dir, - 1 ) == "/")
			$dir .= "x";
		if (! is_dir ( $dir ))
			mkdir ( $dir );
		$dir .= "/";
		return $dir;
	}

	/**
	 * Returns url base to get urls to files attached with anonce
	 *
	 * @return string
	 */
	public function getFilesUrl() {
		$dir = $this->site->staticUrl ( "f" );
		$dir .= "/" . substr ( $this->id, 0, 1 );
		$dir .= "/" . substr ( $this->id, 1, 2 );
		if (substr ( $dir, - 1 ) == "/")
			$dir .= "x";
		$dir .= "/";
		return $dir;
	}

	/**
	 * Sets access conditions to anonces query
	 *
	 * @param O_Dao_Query $q
	 */
	static public function setQueryAccesses(O_Dao_Query $q) {
		if (! R_Mdl_Session::isLogged ()) {
			$q->where ( "anonymous_access & ?", self::ACTION_READ );
			return;
		}
		$user = R_Mdl_Session::getUser ();
		$res = static::getTableInfo()->getTableName ();
		$rel = R_Mdl_User_Relation::getTableInfo()->getTableName ();
		$usr = $user->id;

		$q->joinOnce ( $rel, "$res.site=$rel.site AND $rel.user=" . $user->id );
		$q->where("
			$rel.flags & 2 = 0 AND (
				$res.owner=$usr
				OR ($res.groups & $rel.groups > 0 AND $res.groups_access & 1 = 1)
				OR ($res.logged_access & 1 = 1 AND NOT ($res.groups & $rel.groups = 1 AND $res.groups_access & 1 = 0))
			)
		");
	}

	/**
	 * Sets anonce position in collection
	 *
	 * @param int $newPosition
	 */
	public function setPosition($newPosition) {
		if ($newPosition == $this->position)
			return;
		if ($newPosition <= 0 || $newPosition > count ( $this->collection->anonces ) + 1)
			return;
			/* @var $anonces O_Dao_Query */
		$anonces = $this->collection->anonces;

		if ($newPosition > $this->position) {
			$anonces->test ( "position", $this->position, ">" )->test ( "position", $newPosition, "<=" )->field ( "position", "position-1", 1 )->update ();
		} else {
			$anonces->test ( "position", $this->position, "<" )->test ( "position", $newPosition, ">=" )->field ( "position", "position+1", 1 )->update ();
		}

		$this->position = $newPosition;
		parent::save ();
	}

	public function getNext() {
		$this->system->anonces->reload();
		return $this->getNextOrPrev ( 0 );
	}

	/**
	 * @return R_Mdl_Site_Anonce
	 */
	public function getPrevious() {
		return $this->getNextOrPrev ( 1 );
	}

	private function getNextOrPrev($prev) {
		if ($prev) {
			$op_test = "<";
			$op_ord_pos = " DESC";
		} else {
			$op_test = ">";
			$op_ord_pos = "";
		}
		$anonce = null;
		if ($this->collection) {
			$coll = $this->collection->anonces->test ( "position", $this->position, $op_test )->clearOrders ()->orderBy ( "position" . $op_ord_pos );
			$this->addQueryAccessChecks( $coll );
			$anonce = $coll->getOne ();
			if ($anonce)
				return $anonce;
			$coll = $this->system->collections->test ( "position", $this->collection->position, $op_test )->clearOrders ()->orderBy ( "position" . $op_ord_pos )->getOne ();
			if ($coll) {
				$coll = $coll->anonces->clearOrders ()->orderBy ( "position" . $op_ord_pos );
				$this->addQueryAccessChecks( $coll );
				$anonce = $coll->getOne ();
				if ($anonce)
					return $anonce;
			}
			return null;
		}
		$q = $this->system->anonces->test ( "time", $this->time, $op_test )->clearOrders()->orderBy ( "time" . $op_ord_pos );
		$this->addQueryAccessChecks( $q );
		return $q->getOne ();
	}







	/**
	 * 					ACCESS MIGRATIONS
	 */

	const ACTION_READ = 1;
	const ACTION_WRITE = 2;
	const ACTION_DELETE = 4;
	const ACTION_COMMENT = 8;
	const ACTION_ADMIN = 16;

	private function setMigrateAccesses() {
		switch($this["access_comment"]){
			case "protected":
				$this->allowToGroups(self::ACTION_COMMENT);
				$this->allowToLogged(self::ACTION_COMMENT);
				break;
			case "private":
				$this->allowToGroups(self::ACTION_COMMENT);
				$this->denyToLogged(self::ACTION_COMMENT);
				break;
			case "disable":
				$this->denyToGroups(self::ACTION_COMMENT);
				$this->denyToLogged(self::ACTION_COMMENT);
				break;
		}
		switch($this["access"]){
			case "public":
				$this->allowToGroups(self::ACTION_READ);
				$this->allowToLogged(self::ACTION_READ);
				$this->allowToAnonymous(self::ACTION_READ);
				break;
			case "protected":
				$this->allowToGroups(self::ACTION_READ);
				$this->allowToLogged(self::ACTION_READ);
				$this->denyToAnonymous(self::ACTION_READ);
				break;
			case "private":
				$this->allowToGroups(self::ACTION_READ);
				$this->denyToLogged(self::ACTION_READ);
				$this->denyToAnonymous(self::ACTION_READ);
				break;
			case "disable":
				$this->denyToGroups(self::ACTION_READ);
				$this->denyToLogged(self::ACTION_READ);
				$this->denyToAnonymous(self::ACTION_READ);
				break;
		}
	}

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
		$q->join($rel, "$rel.user=".$user->id." AND $rel.site=$res.site");
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
	 * Adds access checks to query in current resource context
	 *
	 * @param O_Dao_Query $q
	 * @return O_Dao_Query
	 */
	public function addQueryAccessChecks(O_Dao_Query $q) {
		if(R_Mdl_Session::isLogged()) {
			$rel = R_Mdl_Session::getUser()->getSiteRelation($this->site);
			$q->where("owner=? OR (groups & ? > 0 AND groups_access & 1 = 1) OR (logged_access & 1 = 1 AND NOT (groups & ? = 1 AND groups_access & 1 = 0))", R_Mdl_Session::getUser(), $rel->groups, $rel->groups);
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
		return $this->site->groups->test("flag", $this->groups, "&");
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
	public function allowToGroups($action) {
		$this->groups_access = $this->groups_access | $action;
	}

	/**
	 * Denies $action for groups
	 *
	 * @param const $action
	 */
	public function denyToGroups($action) {
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
	public function allowToLogged($action) {
		$this->logged_access = $this->logged_access | $action;
	}

	/**
	 * Denies $action for logged users
	 *
	 * @param const $action
	 */
	public function denyToLogged($action) {
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
	public function allowToAnonymous($action) {
		$this->anonymous_access = $this->anonymous_access | $action;
	}

	/**
	 * Denies $action for not-logged users
	 *
	 * @param const $action
	 */
	public function denyToAnonymous($action) {
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
	public function aclUserCan($action, O_Acl_iUser $user=null) {
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