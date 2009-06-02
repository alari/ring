<?php
/**
 * @table info_page -show-loop:callback container ul
 *
 * @field title VARCHAR(255) -edit -show-def container h1 -show-loop linkInContainer li -title Название и заголовок странички
 * @field content MEDIUMTEXT -edit wysiwyg Libro 650 -check htmlPurify -show-def -title
 * 
 * @field topics -has many R_Mdl_Info_Topic -inverse pages -edit R_Fr_Info_Topic::editList -title Рубрики
 * 
 * @index title -unique
 */
class R_Mdl_Info_Page extends O_Dao_ActiveRecord {

	public function __construct($title) {
		$this["title"] = $title;
		parent::__construct();
	}
	
	public function url($mode="") {
		return O_UrlBuilder::get(($mode?$mode.":":"").urlencode(strtr($this->title, " ", "_")));
	}
	
	static public function getByUrlName($name) {
		return self::getByTitle(strtr(urldecode($name), "_", " "));
	}
	
	static public function getByTitle($title) {
		return O_Dao_Query::get(__CLASS__)->test("title", $title)->getOne();;
	}
	
	
}