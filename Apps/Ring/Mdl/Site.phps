<?php
/**
 * @table site
 * @field host varchar(255) NOT NULL
 * @field owner -has one R_Mdl_User -inverse site
 * @field owner_friends -alias owner.friends
 * @field owner_friends_friends -alias owner.friends.friends
 *
 * @field static_urlbase varchar(255) NOT NULL
 * @field static_folder varchar(255) NOT NULL
 * @field copyright varchar(255) NOT NULL DEFAULT 'Copyright holders' -edit -required Введите копирайт автора или авторов сайта -title Копирайты
 * @field systems -owns many R_Mdl_Site_System -inverse site -order-by position
 * @field title varchar(255) NOT NULL DEFAULT 'Сайт' -edit -required Введите название сайта -title Название сайта
 *
 * @field about varchar(255) NOT NULL DEFAULT 'О сайте' -edit -required Введите название -title Название страницы "о сайте"
 * @field about_page -owns one R_Mdl_Site_About -inverse site
 *
 * @field blogs -owns many R_Mdl_Blog -inverse site
 *
 * @field tags -owns many R_Mdl_Tag -inverse site
 *
 * @index host -unique
 */
class R_Mdl_Site extends O_Dao_ActiveRecord {
	private $available_systems;

	public function __construct( $host )
	{
		if (substr( $host, 0, 7 ) == "http://")
			$host = substr( $host, 7 );
		if (strpos( $host, "/" ))
			$host = substr( $host, 0, strpos( $host, "/" ) );
		if ($host)
			$this->host = $host;
			// TODO get patterns from registry, create folder, copy default CSS into it
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

	static public function getByHost( $host )
	{
		if (substr( $host, 0, 7 ) == "http://")
			$host = substr( $host, 7 );
		if (strpos( $host, "/" ))
			$host = substr( $host, 0, strpos( $host, "/" ) );
		if (!$host)
			return;
		return O_Dao_Query::get( __CLASS__ )->test( "host", $host )->getOne();
	}

	public function url( $sub = "", array $params = array() )
	{
		if (count( $params ))
			$sub .= "?" . O_UrlBuilder::buildQueryString( $params );
		return "http://" . $this->host . "/" . $sub;
	}

	public function staticUrl( $file )
	{
		return $this->url( $this->static_urlbase . $file );
	}

	public function delete()
	{
		$dir = substr( $this->static_folder, 0, -1 );
		parent::delete();
		$this->rmdir( $dir );
	}

	public function getSystems()
	{
		if (!$this->available_systems) {
			$accesses = Array ();
			foreach (array_keys( R_Mdl_Site_System::getAccesses() ) as $acc) {
				if (R_Mdl_Session::can( "read " . $acc, $this ))
					$accesses[] = $acc;
			}
			$this->available_systems = count( $accesses ) ? $this->systems->test( "access", $accesses ) : array ();
		}
		return $this->available_systems;
	}

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