<?php
/**
 * @table site
 * @field host varchar(255) NOT NULL
 * @field owner -has one R_Mdl_User -inverse site
 * @field owner_friends -alias owner.friends
 * @field owner_friends_friends -alias owner.friends.friends
 *
 * @field systems -owns many R_Mdl_Site_System -inverse site -order-by position
 * @field tags -owns many R_Mdl_Site_Tag -inverse site
 * @field anonces -owns many R_Mdl_Site_Anonce -inverse site -order-by time desc
 *
 * @field about varchar(255) NOT NULL DEFAULT 'О сайте' -edit -required Введите название -title Название страницы "о сайте"
 * @field about_page -owns one R_Mdl_Site_About -inverse site
 *
 * @field static_urlbase varchar(255) NOT NULL
 * @field static_folder varchar(255) NOT NULL
 * @field copyright varchar(255) NOT NULL DEFAULT 'Copyright holders' -edit -required Введите копирайт автора или авторов сайта -title Копирайты
 * @field title varchar(255) NOT NULL DEFAULT 'Сайт' -edit -required Введите название сайта -title Название сайта
 *
 * @index host -unique
 */
class R_Mdl_Site extends O_Dao_ActiveRecord {
	private $available_systems;

	/**
	 * Creates new site
	 *
	 * @param string $host
	 */
	public function __construct( $host )
	{
		if (substr( $host, 0, 7 ) == "http://")
			$host = substr( $host, 7 );
		if (strpos( $host, "/" ))
			$host = substr( $host, 0, strpos( $host, "/" ) );
		if ($host)
			$this->host = $host;

		$this->static_urlbase = O_Registry::get( "app/sites/static_urlbase" ) . "$host/";
		$this->static_folder = O_Registry::get( "app/sites/static_folder" ) . "$host/";

		parent::__construct();

		if (!is_dir( substr( $this->static_folder, 0, -1 ) ))
			mkdir( substr( $this->static_folder, 0, -1 ), 0777 );
		$style = file_get_contents( O_Registry::get( "app/sites/static_folder" ) . "style.css" );
		$style = str_replace( "{%STATIC_PROJ%}", O_Registry::get( "app/html/static_root" ), $style );
		file_put_contents( $this->static_folder . "style.css", $style );
		$this->about_page = new R_Mdl_Site_About( );
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
		return O_Dao_Query::get( __CLASS__ )->test( "host", $host )->getOne();
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
		return $this->url( $this->static_urlbase . $file );
	}

	/**
	 * Deletes the entire site
	 *
	 */
	public function delete()
	{
		$dir = substr( $this->static_folder, 0, -1 );
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
			R_Mdl_Session::setQueryAccesses( $this->available_systems, $this );
		}
		return $this->available_systems;
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

}