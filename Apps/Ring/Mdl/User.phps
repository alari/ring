<?php
/**
 * @table user
 * @field site -owns one R_Mdl_Site -inverse owner
 *
 * @field email VARCHAR(255) -edit -title Адрес электронной почты
 * @field nickname VARCHAR(255) -edit -title Ник или псевдоним
 * @field avatar_ext ENUM('-','gif','jpeg','png') DEFAULT '-'
 *
 * @field friends -has many R_Mdl_User -inverse friend_of
 * @field friend_of -has many R_Mdl_User -inverse friends
 * @field friends_friends -alias friends.friends
 *
 * @field anonces -owns many R_Mdl_Site_Anonce -inverse owner
 *
 * @field blog_comments -owns many R_Mdl_Blog_Comment -inverse owner
 */
class R_Mdl_User extends O_Acl_User {

	public function __construct( $identity, O_Acl_Role $role )
	{
		O_OpenId_Provider_UserPlugin::normalize( $identity );
		$this->identity = $identity;
		$this->role = $role;
		parent::__construct();
		$this->nickname = $identity;
		$this->createUserdir();
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

	public function avatarUrl( $full = false )
	{
		$src = "ava" . ($full ? "-full" : "") . "." . ($this->avatar_ext != "-" ? $this->avatar_ext : "gif");
		return $this->avatar_ext != "-" ? $this->staticUrl( $src ) : O_Registry::get( "app/users/static_urlbase" ) . $src;
		return O_UrlBuilder::getStatic( $src );
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
		echo "<a href=\"" . $this->url() . "\">" . $this->nickname . "</a>";
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