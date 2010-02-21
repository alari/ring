<?php
/**
 * @table systems
 *
 * @field site -has one R_Mdl_Site -inverse systems
 * @field anonces -owns many R_Mdl_Site_Anonce -inverse system -order-by time DESC
 *
 * @field collections -owns many R_Mdl_Site_Collection -inverse system -order-by position
 *
 * @field instance -one-of blog; im; libro; sound; afisha
 * @field blog -owns one R_Mdl_Sys_Blog_System -inverse system
 * @field im -owns one R_Mdl_Sys_Im_System -inverse system
 * @field libro -owns one R_Mdl_Sys_Libro_System -inverse system
 * @field sound -owns one R_Mdl_Sys_Sound_System -inverse system
 * @field afisha -owns one R_Mdl_Sys_Afisha_System -inverse system
 *
 * @field title varchar(128) NOT NULL
 * @field position tinyint NOT NULL
 * @field urlbase varchar(128) NOT NULL
 * @field access ENUM('public','protected','private','disable') DEFAULT 'public' -enum public: Всем; protected: Авторизованным; private: Друзьям; disable: Только себе
 *
 * @index site,position,access
 * @index site,urlbase -unique
 */
class R_Mdl_Sys_Instance extends O_Dao_ActiveRecord {
	private static $classes = Array ("blog" => "R_Mdl_Sys_Blog_System", "im" => "R_Mdl_Sys_Im_System",
									"sound" => "R_Mdl_Sys_Sound_System",
									"libro" => "R_Mdl_Sys_Libro_System",
									"afisha" => "R_Mdl_Sys_Afisha_System");
	private static $titles = Array ("blog" => "Блог", "im" => "Изображения", "sound" => "Музыка",
									"libro" => "Литература", "afisha" => "Афиша");
	private static $accesses = Array ("public" => "Всем", "protected" => "Друзьям и друзьям друзей",
									"private" => "Друзьям", "disable" => "Только себе");

	public function getType()
	{
		return self::$titles[ O_Dao_TableInfo::get( $this )->getFieldInfo( "instance" )->getRealField(
				$this ) ];
	}

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
		$this->position = count( $site->systems ) + 1;
		$this->site = $site;
		parent::__construct();

		$this->createResource();
	}

	public function createResource() {
		$res = new R_Mdl_Resource($this->site);
		$this->site->getResource()->injectBottom($res);
		$res->type = R_Mdl_Resource::TYPE_SYSTEM;
		$res->groups = 3;
		$res->show_to_followers = 0;
		$res->setContent($this);
		$time = $this->anonces->getFunc("time", "MIN");
		if(!$time) $time = time();
		$res->time = $time;
		$this->syncRes($res);
	}

	private function syncRes(R_Mdl_Resource $res=null) {
		if(!$res) $res = $this->getResource();
		if(!$res) return;
		$res->title = $this->title;
		$res->url_part = $this->urlbase;
		$res->url_cache = $this->urlbase;

		$act = R_Mdl_Resource::ACTION_READ;

		switch($this["access"]){
			case "public":
				$res->allowToGroups($act);
				$res->allowToLogged($act);
				$res->allowToAnonymous($act);
				break;
			case "protected":
				$res->allowToGroups($act);
				$res->allowToLogged($act);
				$res->denyToAnonymous($act);
				break;
			case "private":
				$res->allowToGroups($act);
				$res->denyToLogged($act);
				$res->denyToAnonymous($act);
				break;
			case "disable":
				$res->denyToGroups($act);
				$res->denyToLogged($act);
				$res->denyToAnonymous($act);
				break;
		}

		$res->getChilds()->test("type", R_Mdl_Resource::TYPE_COLLECTION)
			->field("groups_access", $res->groups_access)
			->field("logged_access", $res->logged_access)
			->field("anonymous_access", $res->anonymous_access)
			->update();

		$res->save();
	}

	/**
	 * @return R_Mdl_Resource
	 */
	public function getResource() {
		return $this->site->nodes->test("content", $this->id)->test("content_class", __CLASS__)->getOne();
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

	public function delete()
	{
		$this->site->systems->test( "position", $this->position, ">" )->field( "position",
				"position-1", true )->update();
		$this->getResource()->delete();
		parent::delete();
	}

	public function save() {
		parent::save();
		$this->syncRes();
	}

	/**
	 * Sets system position
	 *
	 * @param int $newPosition
	 */
	public function setPosition( $newPosition )
	{
		if ($newPosition == $this->position)
			return;
		if ($newPosition <= 0 || $newPosition > count( $this->site->systems ) + 1)
			return;

		$systems = $this->site->systems;

		if ($newPosition > $this->position) {
			$systems->test( "position", $this->position, ">" )->test( "position", $newPosition,
					"<=" )->field( "position", "position-1", 1 )->update();
		} else {
			$systems->test( "position", $this->position, "<" )->test( "position", $newPosition,
					">=" )->field( "position", "position+1", 1 )->update();
		}

		$this->position = $newPosition;
		parent::save();
	}

}