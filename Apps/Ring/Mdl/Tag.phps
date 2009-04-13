<?php
/**
 * @table tags
 * @field site -has one R_Mdl_Site -inverse tags
 * @field title VARCHAR(64) NOT NULL -edit -required -title Название метки
 * @field description VARCHAR(255) -edit -title Описание или расшифровка
 * @field weight int NOT NULL DEFAULT 0
 *
 * @field blog_posts -has many R_Mdl_Blog_Post -inverse tags
 * @field blog_anonces -has many R_Mdl_Blog_Anonce -inverse tags
 *
 * @index site,weight
 * @index weight
 * @index title
 * @index site,title -unique
 */
class R_Mdl_Tag extends O_Dao_ActiveRecord {

	public function __construct( R_Mdl_Site $site )
	{
		$this->setField( "site", $site->id );
		$this->title = '';
		parent::__construct();
		$this->site = $site;
	}

	public function url()
	{
		return $this->site->url( "tag/" . urlencode( $this->title ) );
	}

	public function link()
	{
		return "<a href=\"" . $this->url() . "\"" . ($this->description ? ' title="' . htmlspecialchars( 
				$this->description ) . '"' : '') . ">" . $this->title . "</a>";
	}

}