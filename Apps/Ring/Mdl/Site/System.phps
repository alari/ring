<?php
/**
 * @table system
 * @field site -has one R_Mdl_Site -inverse systems
 *
 * @field title varchar(128) NOT NULL
 * @field position tinyint NOT NULL
 * @field urlbase varchar(128) NOT NULL
 * @field access ENUM('public','protected','private','disable') DEFAULT 'public'
 *
 * @field type varchar(16)
 *
 * @field blog -owns one R_Mdl_Blog -inverse system
 *
 * @index site,position
 */
class R_Mdl_Site_System extends O_Dao_ActiveRecord {
	private $system;
	
	private static $classes = Array ("blog" => "R_Mdl_Blog");
	private static $titles = Array ("blog" => "Блог");
	private static $accesses = Array ("public" => "Всем", "protected" => "Друзьям друзей", "private" => "Друзьям", 
									"disable" => "Себе");

	static public function getClasses()
	{
		return self::$classes;
	}

	static public function getTitles()
	{
		return self::$titles;
	}

	static public function getAccesses()
	{
		return self::$accesses;
	}

	public function __construct( $title, $urlbase, R_Mdl_Site $site )
	{
		$this->title = $title;
		$this->urlbase = $urlbase;
		$this->position = count( $site->systems );
		parent::__construct();
		$this->site = $site;
	}

	public function getSystem()
	{
		if (!$this->system)
			$this->system = $this->{$this->type};
		return $this->system;
	}

	public function setSystem( O_Dao_ActiveRecord $system )
	{
		$field = array_search( get_class( $system ), self::$classes );
		if ($field) {
			$oldField = array_search( $this->system_class, self::$classes );
			if ($oldField) {
				$this->$oldField = null;
			}
			
			$this->$field = $system;
			$this->type = $field;
			return $this->save();
		}
		return false;
	}

	public function handleRequest( $page )
	{
		$cmd = $this->getSystem()->getCommand( $page );
		return $cmd->run();
	}

	public function url( $sub = "" )
	{
		return $this->site->url( $this->urlbase . "/" . $sub );
	}

	public function link()
	{
		return "<a href=\"" . $this->url() . "\">$this->title</a>";
	}

}