<?php
/**
 * @table systems -show:callback R_Fr_Site_System::showSelf
 *
 * @field site -has one R_Mdl_Site -inverse systems
 * @field anonces -owns many R_Mdl_Site_Anonce -inverse system -show-home -order-by time DESC
 *
 * @field collections -owns many R_Mdl_Site_Collection -inverse system
 *
 * @field instance -one-of blog; im
 * @field blog -owns one R_Mdl_Blog -inverse system
 * @field im -owns one R_Mdl_Im -inverse system
 *
 * @field title varchar(128) NOT NULL
 * @field position tinyint NOT NULL
 * @field urlbase varchar(128) NOT NULL
 * @field access ENUM('public','protected','private','disable') DEFAULT 'public' -enum public: Всем; protected: Друзьям и друзьям друзей; private: Друзьям; disable: Только себе
 *
 * @index site,position,access
 * @index site,urlbase -unique
 */
class R_Mdl_Site_System extends O_Dao_ActiveRecord {
	private static $classes = Array ("blog" => "R_Mdl_Blog");
	private static $titles = Array ("blog" => "Блог");
	private static $accesses = Array ("public" => "Всем", "protected" => "Друзьям друзей", "private" => "Друзьям",
									"disable" => "Себе");

	/**
	 * Returns url for creative instance
	 *
	 * @param int $creative_id
	 */
	public function creativeUrl( $creative_id )
	{
		return $this->url( $creative_id );
	}

	/**
	 * Returns array of possible instance classes
	 *
	 * @return array
	 */
	static public function getClasses()
	{
		return self::$classes;
	}

	/**
	 * Returns array of system instances titles
	 *
	 * @return array
	 */
	static public function getTitles()
	{
		return self::$titles;
	}

	/**
	 * Returns array of access titles
	 *
	 * @return array
	 */
	static public function getAccesses()
	{
		return self::$accesses;
	}

	/**
	 * Creates new system
	 *
	 * @param string $title
	 * @param string $urlbase
	 * @param R_Mdl_Site $site
	 */
	public function __construct( $title, $urlbase, R_Mdl_Site $site )
	{
		$this->title = $title;
		$this->urlbase = $urlbase;
		$this->position = count( $site->systems );
		parent::__construct();
		$this->site = $site;
	}

	/**
	 * Returns url for page inside the system
	 *
	 * @param string $sub
	 * @return string
	 */
	public function url( $sub = "" )
	{
		return $this->site->url( $this->urlbase . "/" . $sub );
	}

	/**
	 * Returns link to the system
	 *
	 * @return string
	 */
	public function link()
	{
		return "<a href=\"" . $this->url() . "\">$this->title</a>";
	}
}