<?php
/**
 * @table site -show-loop:callback R_Fr_Site::showInLoop -edit:submit Сохранить изменения
 * @field host varchar(255) NOT NULL
 * @field owner -has one _User -inverse site
 * @field owner_friends -alias relations.user -where groups & 2
 *
 * @field groups -owns many _User_Group -inverse site
 *
 * @field relations -owns many _User_Relation -inverse site
 *
 * @field members -alias relations.user -where groups & 2
 * @field admins -alias relations.user -where groups & 1
 * @field leader -alias relations.user -where groups & 1 and flags & 16
 *
 * @field systems -owns many R_Mdl_Sys_Instance -inverse site -order-by position
 * @field tags -owns many R_Mdl_Site_Tag -inverse site
 * @field anonces -owns many R_Mdl_Site_Anonce -inverse site -order-by time DESC
 *
 * @field crosspost_services -owns many R_Mdl_Site_Crosspost_Service -inverse site
 *
 * @field about varchar(255) NOT NULL DEFAULT 'О сайте' -edit -required Введите название -title Название страницы "о сайте"
 * @field about_page -owns one R_Mdl_Site_About -inverse site
 *
 * @field type TINYINT DEFAULT 1 -enum 1: Авторский; 2: Сообщество -edit-adm enum -title Тип сайта
 * @field status TINYINT DEFAULT 0 -enum 0: Неотмодерирован; 1: Прошёл модерацию; 2: Технический сайт -edit-adm enum -title Статус сайта
 *
 * @field copyright varchar(255) NOT NULL DEFAULT 'Copyright holders' -edit -required Введите копирайт автора или авторов сайта -title Копирайты
 * @field title varchar(255) NOT NULL DEFAULT 'Сайт' -edit -required Введите название сайта -title Название сайта
 *
 * @field ava_full ENUM('-','gif','jpeg','png') DEFAULT '-' -image filepath: avaPath full; src: avaSrc full; width:190; height:500; cascade: ava_tiny; clear:1 -edit -title Картинка-знак сайта
 * @field ava_tiny -image filepath: avaPath tiny; src: avaSrc tiny; width:80; height:200
 *
 * @index host -unique
 * @index status
 * @index type
 */
class R_Mdl_Site extends O_Dao_NestedSet_Root {

	const TYPE_AUTH = 1;
	const TYPE_COMM = 2;
	const ST_MODERATED = 1;
	const ST_AWAITING = 0;
	const ST_TECH = 2;

	const NODES_CLASS = "R_Mdl_Resource";

	private $available_systems;

	/**
	 * Creates new site
	 *
	 * @param string $host
	 */
	public function __construct( $host, $owner, $type=self::TYPE_AUTH )
	{
		if (substr( $host, 0, 7 ) == "http://")
			$host = substr( $host, 7 );
		if (strpos( $host, "/" ))
			$host = substr( $host, 0, strpos( $host, "/" ) );
		if ($host)
			$this->host = $host;

		$this->type = $type;

		parent::__construct();

		if (!is_dir( substr( $this->staticPath(""), 0, -1 ) ))
			mkdir( substr( $this->staticPath(""), 0, -1 ), 0777 );
		$style = file_get_contents( O_Registry::get( "app/sites/static_folder" ) . "style.css" );
		$style = str_replace( "{%STATIC_PROJ%}", O_Registry::get( "app/html/static_root" ), $style );
		file_put_contents( $this->staticPath("style.css"), $style );
		$this->about_page = new R_Mdl_Site_About( );

		// Generate default groups
		R_Mdl_User_Group::createSiteGroups($this, $owner);

		$this->createResource();
	}

	public function createResource() {
		if($this->getResource()) return;
		// CREATE ROOT RESOURCE
		$res = new R_Mdl_Resource($this);
		$res->type = R_Mdl_Resource::TYPE_SITE;
		$res->show_to_followers = 0;
		$res->loggedAllow(R_Mdl_Resource::ACTION_READ);
		$res->anonymousAllow(R_Mdl_Resource::ACTION_READ);
		$res->url_cache = "";
		$res->url_part = "";
		$res->title = $this->title;
		$time = $this->anonces->getFunc("time", "MIN");
		if(!$time) $time = time();
		$res->time = $time;
		$res->save();
	}

	public function save() {
		parent::save();
		$res = $this->getResource();
		if($res) {
			$res->title = $this->title;
			$res->save();
		}
	}

	/**
	 * Returns root resource
	 *
	 * @return R_Mdl_Resource
	 */
	public function getResource() {
		return $this->nodes->test("level", 0)->getOne();
	}

	/**
	 * Returns site instance by its host name
	 *
	 * @param string $host
	 * @return R_Mdl_Site
	 */
	static public function getByHost( $host )
	{
		if (substr( $host, 0, 7 ) == "http://")
			$host = substr( $host, 7 );
		if (strpos( $host, "/" ))
			$host = substr( $host, 0, strpos( $host, "/" ) );
		if (!$host)
			return null;
		return O_Dao_Query::get( __CLASS__ )->test( "host", $host )->limit(1)->getOne();
	}

	public function avaSrc( $type )
	{
		if ($this[ "ava_full" ] == "-") {
			if($this->owner) return $this->owner->avaSrc($type);
			return O_UrlBuilder::getStatic ( "im/comm-" . $type . ".png" );
		}
		return $this->staticUrl( "ava-" . $type . "." . $this[ "ava_full" ] );
	}

	public function avaPath( $type, $ext = null )
	{
		return $this->staticPath( "ava-" . $type . ($ext ? $ext : "." . $this[ "ava_full" ]) );
	}

	public function avatar( $full = false )
	{
		return "<img class=\"avatar\" src=\"" . ($full ? $this->ava_full : $this->ava_tiny) . "\" alt=\"" .
				 htmlspecialchars( $this->title ) . "\"/>";
	}

	public function link() {
		$img = O_UrlBuilder::getStatic ( "im/".($this["type"]==self::TYPE_COMM?"comm":"auth").".gif" );
		$img = "<img src=\"$img\" width=\"11\" height=\"11\" alt=\"\"/>&nbsp;";
		return $img."<a href=\"".$this->url()."\">".trim($this->title)."</a>";
	}


	/**
	 * Returns url inside the site
	 *
	 * @param string $sub
	 * @param array $params
	 * @return string
	 */
	public function url( $sub = "", array $params = array() )
	{
		if (count( $params ))
			$sub .= "?" . O_UrlBuilder::buildQueryString( $params );
		if (!$sub || $sub[ 0 ] != "/")
			$sub = "/" . $sub;
		return "http://" . $this->host . $sub;
	}

	/**
	 * Returns url for site static file
	 *
	 * @param string $file
	 * @return string
	 */
	public function staticUrl( $file )
	{
		return "http://fl.centralis.name/s/". $this["id"] . "/" . $file;
	}

	/**
	 * Path for a file attached with site
	 *
	 * @param string $file
	 * @return string
	 */
	public function staticPath( $file )
	{
		return "../fl.utils.mir.io/s/". $this["id"] . "/" . $file;
	}

	/**
	 * Deletes the entire site
	 *
	 */
	public function delete()
	{
		$dir = "../fl.utils.mir.io/s/".$this["id"];
		parent::delete();
		$this->rmdir( $dir );
	}

	/**
	 * Returns array of currently available systems
	 *
	 * @return array
	 */
	public function getSystems()
	{
		if (!$this->available_systems) {
			$this->available_systems = $this->systems->query();
			self::setQueryAccesses( $this->available_systems, $this );
		}
		return $this->available_systems;
	}

	static public function setQueryAccesses( O_Dao_Query $query, R_Mdl_Site $site )
	{
		$accesses = Array ();
		foreach (array_keys( R_Mdl_Sys_Instance::getAccesses() ) as $acc) {
			if (R_Mdl_Session::can( "read " . $acc, $site ))
				$accesses[] = $acc;
		}
		return $query->test( "access", count( $accesses ) ? $accesses : 0 );
	}

	/**
	 * Recursively deletes the folder
	 *
	 * @todo move it somewhere
	 * @param string $dirname
	 */
	private function rmdir( $dirname )
	{
		if (!is_dir( $dirname ))
			return;
		$d = opendir( $dirname );
		while ($f = readdir( $d ))
			if ($f != "." && $f != "..") {
				$f = $dirname . "/" . $f;
				if (is_dir( $f ))
					$this->rmdir( $f );
				else
					unlink( $f );
			}
		rmdir( $dirname );
	}

	/**
	 * Updates site's host
	 * Also updates site owner's identity and password, if needed
	 *
	 * @param string $host
	 * @param string $pwd
	 * @return bool
	 */
	public function setHost( $host, $pwd = "12345" )
	{
		if (substr( $host, 0, 7 ) == "http://")
			$host = substr( $host, 7 );
		if (strpos( $host, "/" ))
			$host = substr( $host, 0, strpos( $host, "/" ) );
		if (!$host || $host == $this->host)
			return;

		$old_host = $this->host;
		$this->host = $host;
		try {
			$this->save();
		}
		catch (PDOException $e) {
			return false;
		}

		if ($this->owner && R_Mdl_User::getByIdentity( $old_host ) == $this->owner) {
			$this->owner->setIdentity( $this->host, $pwd );
		}

		$this->save();
		return true;
	}



	/**
	 * Returns one of always created typical groups
	 *
	 * @param const $type
	 * @return R_Mdl_User_Group
	 */
	public function getGroupByFlag($flag) {
		return $this->groups->test("flag", $flag)->getOne();
	}

	/**
	 * Returns user relation
	 *
	 * @param R_Mdl_User $user
	 * @return R_Mdl_User_Relation
	 */
	public function getUserRelation(R_Mdl_User $user) {
		return R_Mdl_User_Relation::getRelation($user, $this, 0);
	}
}
