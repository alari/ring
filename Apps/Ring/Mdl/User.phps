<?php
/**
 * @table user
 * @field site -owns one R_Mdl_Site -inverse owner
 *
 * @field email VARCHAR(255) -edit -title Адрес электронной почты
 * @field nickname VARCHAR(255) -edit -title Ник или псевдоним
 *
 * @field friends -has many R_Mdl_User -inverse friend_of
 * @field friend_of -has many R_Mdl_User -inverse friends
 * @field friends_friends -alias friends.friends
 *
 * @field msgs_own -owns many R_Mdl_Msg -inverse owner -order-by time DESC
 * @field msgs_target -owns many R_Mdl_Msg -inverse target
 *
 * @field anonces -owns many R_Mdl_Site_Anonce -inverse owner
 *
 * @field favorites -has many R_Mdl_Site_Anonce -inverse in_favorites
 *
 * @field comments -owns many R_Mdl_Site_Comment -inverse owner
 *
 * @field ava_full ENUM('-','gif','jpeg','png') DEFAULT '-' -image filepath: avaPath full; src: avaSrc full; width:190; height:500; cascade: ava_tiny; clear:1
 * @field ava_tiny -image filepath: avaPath tiny; src: avaSrc tiny; width:80; height:200
 */
class R_Mdl_User extends O_Acl_User {

	public function __construct( $identity, O_Acl_Role $role = null )
	{
		if (!$role)
			$role = O_Acl_Role::getByName( "OpenId User" );
		O_OpenId_Provider_UserPlugin::normalize( $identity );
		$this->identity = $identity;
		$this->role = $role;
		parent::__construct();
		$this->nickname = rtrim( substr( $identity, 7 ), "/" );
		$this->createUserdir();
	}

	public function avaSrc( $type )
	{
		if ($this[ "ava_full" ] == "-")
			return O_Registry::get( "app/users/static_urlbase" ) . "ava-" . $type . ".gif";
		return $this->staticUrl( "ava-" . $type . "." . $this[ "ava_full" ] );
	}

	public function avaPath( $type, $ext = null )
	{
		return $this->staticFilename( "ava-" . $type . ($ext ? $ext : "." . $this[ "ava_full" ]) );
	}

	/**
	 * Sets password for user
	 *
	 * @param string $pwd
	 * @return bool
	 */
	public function setPwd( $pwd )
	{
		$provider = new O_OpenId_Provider( );
		return $provider->register( $this->identity, $pwd );
	}

	/**
	 * Returns true if user was registered on our site, false -- if it's other openid user
	 *
	 * @return bool
	 */
	public function isOurUser()
	{
		return (bool)$this->pwd_hash;
	}

	/**
	 * Logs in (sets user to session)
	 *
	 * @param string $pwd
	 * @param O_OpenId_Provider $provider
	 * @return bool
	 */
	public function login( $pwd, O_OpenId_Provider $provider = null )
	{
		if (!$provider)
			$provider = new O_OpenId_Provider( );
		return $provider->login( $this->identity, $pwd );
	}

	/**
	 * Returns profile url
	 *
	 * @return string
	 */
	public function url()
	{
		if ($this[ "site" ]) {
			return $this->site->url( "profile" );
		} else {
			return $this->identity;
		}
	}

	public function avatar( $full = false )
	{
		return "<img src=\"" . ($full ? $this->ava_full : $this->ava_tiny) . "\" alt=\"" . htmlspecialchars( $this->nickname ) . "\"/>";
	}

	public function staticUrl( $filename )
	{
		return O_Registry::get( "app/users/static_urlbase" ) . $this->id . "/" . $filename;
	}

	public function staticFilename( $filename )
	{
		return O_Registry::get( "app/users/static_folder" ) . $this->id . "/" . $filename;
	}

	public function createUserdir()
	{
		if (!is_dir( O_Registry::get( "app/users/static_folder" ) . $this->id )) {
			mkdir( O_Registry::get( "app/users/static_folder" ) . $this->id );
		}
	}

	public function link()
	{
		return "<a href=\"" . $this->url() . "\">" . ($this->nickname ? $this->nickname : $this->identity) . "</a>";
	}

	/**
	 * Returns user by identity
	 *
	 * @param string $identity
	 * @return R_Mdl_User
	 */
	static public function getByIdentity( $identity )
	{
		return O_OpenId_Provider_UserPlugin::getByIdentity( $identity );
	}

}