<?php
/**
 * @table info_topic -loop:envelop container ul -edit:submit Сохранить изменения
 *
 * @field title VARCHAR(255) -edit -show-def сontainer h1 -show-loop linkInContainer li -required Введите! -title Название рубрики
 *
 * @field pages -has many R_Mdl_Info_Page -inverse topics -show-def
 *
 * @index title -unique
 */
class R_Mdl_Info_Topic extends O_Dao_ActiveRecord {

	public function __construct( $title )
	{
		$this[ "title" ] = $title;
		parent::__construct();
	}

	public function url()
	{
		return O_UrlBuilder::get( "topic:" . urlencode( strtr( $this->title, " ", "_" ) ) );
	}

	static public function getByUrlName( $name )
	{
		return self::getByTitle( str_replace( "_", " ", urldecode( $name ) ) );
	}

	static public function getByTitle( $title )
	{
		return O_Dao_Query::get( __CLASS__ )->test( "title", $title )->getOne();
		;
	}

}