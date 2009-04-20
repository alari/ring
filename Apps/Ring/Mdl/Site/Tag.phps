<?php
/**
 * @table tags
 * @field site -has one R_Mdl_Site -inverse tags
 *
 * @field title VARCHAR(64) NOT NULL -edit -required -title Название метки
 * @field description VARCHAR(255) -edit -title Описание или расшифровка
 * @field weight int NOT NULL DEFAULT 0
 *
 * @field anonces -has many R_Mdl_Site_Anonce -inverse tags
 *
 * @index site,weight
 * @index weight
 * @index title
 * @index site,title -unique
 */
class R_Mdl_Site_Tag extends O_Dao_ActiveRecord {

	/**
	 * Creates tag -- unique for each site
	 *
	 * @param R_Mdl_Site $site
	 */
	public function __construct( R_Mdl_Site $site )
	{
		$this[ "site" ] = $site->id;
		$this->title = '';
		parent::__construct();
		$this->site = $site;
	}

	/**
	 * Returns tag url -- for whole site or its concrete system
	 *
	 * @param string $urlbase
	 * @return string
	 */
	public function url( $urlbase = "" )
	{
		return $this->site->url( ($urlbase ? $urlbase . "/" : "") . "tag/" . urlencode( $this->title ) );
	}

	/**
	 * Returns link for a tag
	 *
	 * @param R_Mdl_Site_System $sys
	 * @return string
	 */
	public function link( R_Mdl_Site_System $sys = null )
	{
		return "<a href=\"" . $this->url( $sys ? $sys->urlbase : "" ) . "\"" . ($this->description ? ' title="' . htmlspecialchars( 
				$this->description ) . '"' : '') . ">" . $this->title . "</a>";
	}

}