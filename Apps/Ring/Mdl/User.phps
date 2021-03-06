<?php
/**
 * @table user -edit:submit Сохранить изменения
 * @field site -owns one R_Mdl_Site -inverse owner
 *
 * @field email_confirm -owns one _User_EmailConfirm -inverse owner
 * @field email_confirmed INT(1) DEFAULT 0
 * @field email VARCHAR(255) -edit -title Адрес электронной почты -signal email
 * @field nickname VARCHAR(255) -edit -title Ник или псевдоним
 * @field login VARCHAR(32) -edit -title Логин -check Preg [a-z0-9][-a-z0-9]{3,}[a-z0-9]
 *
 * @field relations -owns many _User_Relation -inverse user
 * @field resources -owns many _Resource -inverse owner
 *
 * @field msgs_own -owns many R_Mdl_User_Msg -inverse owner -order-by time DESC
 * @field msgs_target -owns many R_Mdl_User_Msg -inverse target
 *
 * @field anonces -owns many R_Mdl_Site_Anonce -inverse owner
 *
 * @field favorites -has many R_Mdl_Site_Anonce -inverse in_favorites
 *
 * @field comments -owns many R_Mdl_Site_Comment -inverse owner
 *
 * @field ava_full ENUM('-','gif','jpeg','png') DEFAULT '-' -image filepath: avaPath full; src: avaSrc full; width:190; height:500; cascade: ava_tiny; clear:1
 * @field ava_tiny -image filepath: avaPath tiny; src: avaSrc tiny; width:80; height:200
 *
 * @field created_time INT
 * @field lastlogged_time INT
 *
 * @index email -unique
 * @index login -unique
 *
 * @registry app/dao-listeners/set/email/R_Mdl_User R_Mdl_User_EmailConfirm::eventListener -add
 */
class R_Mdl_User extends O_Acl_User {

	public function __construct($identity, O_Acl_Role $role = null) {
		if (! $role)
			$role = O_Acl_Role::getByName ( "OpenId User" );
		O_OpenId_Provider_UserPlugin::normalize ( $identity );
		$this->identity = $identity;
		$this->role = $role;
		$this->created_time = time();
		parent::__construct ();
		$this->nickname = rtrim ( substr ( $identity, 7 ), "/" );
		$this->createUserdir ();
	}

	public function avaSrc($type) {
		if ($this ["ava_full"] == "-") {
			if (! $this->isOurUser ()) {
				return O_UrlBuilder::getStatic ( "im/openid-" . $type . ".png" );
			} elseif ($this ["site"]) {
				return O_UrlBuilder::getStatic ( "im/auth-" . $type . ".png" );
			} else {
				return O_UrlBuilder::getStatic ( "im/user-" . $type . ".png" );
			}
		}
		return $this->staticUrl ( "ava-" . $type . "." . $this ["ava_full"] );
	}

	public function avaPath($type, $ext = null) {
		return $this->staticFilename ( "ava-" . $type . ($ext ? $ext : "." . $this ["ava_full"]) );
	}

	private function getPasswordHash($pwd){
		return md5 ( $this->id . $pwd );
	}
	/**
	 * Sets password for user
	 *
	 * @param string $pwd
	 * @return bool
	 */
	public function setPwd($pwd) {
		$this->pwd_hash = $this->getPasswordHash($pwd);
		return $this->save ();
	}

	/**
	 * Updates identity and password for user
	 *
	 * @param string $identity
	 * @param string $pwd
	 * @return bool
	 */
	public function setIdentity($identity, $pwd) {
		O_OpenId_Provider_UserPlugin::normalize ( $identity );
		$this->identity = $identity;
		try {
			$this->save ();
		} catch ( PDOException $e ) {
			return false;
		}
		return $this->setPwd ( $pwd );
	}

	/**
	 * Returns true if user was registered on our site, false -- if it's other openid user
	 *
	 * @return bool
	 */
	public function isOurUser() {
		return ( bool ) $this->pwd_hash;
	}

	/**
	 * Logs in (sets user to session)
	 *
	 * @param string $pwd
	 * @return bool
	 */
	public function login($pwd) {
		if ( $this->getPasswordHash($pwd) == $this->pwd_hash) {
			R_Mdl_Session::setUser ( $this );
			$this->lastlogged_time = time();
			$this->save();
			return true;
		}
		return false;
	}

	/**
	 * Returns identity url
	 *
	 * @return string
	 */
	public function url() {
		if ($this ["site"]) {
			return $this->site->url ();
		} else {
			return "http://".O_Registry::get("app/hosts/center")."/~".($this->login?$this->login:"id_".$this->id);
		}
	}

	public function avatar($full = false) {
		return "<img class=\"avatar\" src=\"" . ($full ? $this->ava_full : $this->ava_tiny) . "\" alt=\"" . htmlspecialchars ( $this->nickname ) . "\"/>";
	}

	public function staticUrl($filename) {
		return "http://fl.centralis.name/u/".$this["id"]."/".$filename;
		return O_Registry::get ( "app/users/static_urlbase" ) . $this->id . "/" . $filename;
	}

	public function staticFilename($filename) {
		return "../fl.utils.mir.io/u/".$this["id"]."/".$filename;
		return O_Registry::get ( "app/users/static_folder" ) . $this->id . "/" . $filename;
	}

	public function createUserdir() {
		$dir = substr($this->staticFilename(""), 0, -1);
		if (! is_dir ( $dir )) {
			mkdir ( $dir, 0777 );
		}
	}

	public function link() {
		if (! $this->isOurUser ()) {
			$img = O_UrlBuilder::getStatic ( "im/openid.gif" );
		} elseif ($this ["site"]) {
			$img = O_UrlBuilder::getStatic ( "im/auth.gif" );
		} else {
			$img = O_UrlBuilder::getStatic ( "im/user.gif" );
		}
		return "<img src=\"$img\" width=\"11\" height=\"11\" alt=\"\"/>&nbsp;<a href=\"" . $this->url () . "\">" . trim($this->nickname ? $this->nickname : ($this->identity? $this->identity : $this->login)) . "</a>";
	}

	/**
	 * Adds friendship/follow relation
	 *
	 * @param R_Mdl_User|R_Mdl_Site $object
	 */
	public function addFriend(O_Dao_ActiveRecord $object) {
		// New variant
		if($object instanceof R_Mdl_Site) {
			$this->getSiteRelation($object)->addFlag(R_Mdl_User_Relation::FLAG_FOLLOW);
			if($object->owner && $this->site) {
				$object->owner->getSiteRelation($this->site)->addGroup( $this->site->getGroupByFlag(R_Mdl_User_Group::FLAG_MEMBER) );
			}
		} elseif($object instanceof R_Mdl_User) {
			if($object->site instanceof R_Mdl_Site) {
				$this->getSiteRelation($object->site)->addFlag(R_Mdl_User_Relation::FLAG_FOLLOW);
			}
			if($this->site instanceof R_Mdl_Site) {
				$object->getSiteRelation($this->site)->addGroup( $this->site->getGroupByFlag(R_Mdl_User_Group::FLAG_MEMBER) );
			}
		}
	}

	/**
	 * Removes friendship/follow relation
	 *
	 * @param R_Mdl_User|R_Mdl_Site $object
	 */
	public function removeFriend(O_Dao_ActiveRecord $object) {
		// New variant
		if($object instanceof R_Mdl_Site) {
			$this->getSiteRelation($object)->removeFlag(R_Mdl_User_Relation::FLAG_FOLLOW);
			if($object->owner && $this->site) {
				$object->owner->getSiteRelation($this->site)->removeGroup( $this->site->getGroupByFlag(R_Mdl_User_Group::FLAG_MEMBER) );
			}
		} elseif($object instanceof R_Mdl_User) {
			if($object->site instanceof R_Mdl_Site) {
				$this->getSiteRelation($object->site)->removeFlag(R_Mdl_User_Relation::FLAG_FOLLOW);
			}
			if($this->site instanceof R_Mdl_Site) {
				$object->getSiteRelation($this->site)->removeGroup( $this->site->getGroupByFlag(R_Mdl_User_Group::FLAG_MEMBER) );
			}
		}
	}

	/**
	 * Returns site relation
	 *
	 * @param R_Mdl_Site $site
	 * @return R_Mdl_User_Relation
	 */
	public function getSiteRelation(R_Mdl_Site $site) {
		return R_Mdl_User_Relation::getRelation($this, $site, 0);
	}

	/**
	 * Returns user by identity
	 *
	 * @param string $identity
	 * @return R_Mdl_User
	 */
	static public function getByIdentity($identity) {
		return O_OpenId_Provider_UserPlugin::getByIdentity ( $identity );
	}

	/**
	 * Returns user by email, host or login
	 * @param unknown_type $ident
	 */
	static public function getByStringId($ident) {
		if(strpos($ident, "@")) {
			$field = "email";
		} elseif(strpos($ident, ".")) {
			return self::getByIdentity($ident);
		}elseif(strpos($ident, "_")){ {
			list(,$ident) = explode("_", $ident, 2);
			$field = "id";
		}
		} else {
			$field = "login";
		}
		return static::getQuery()->test($field, $ident)->getOne();
	}
}
