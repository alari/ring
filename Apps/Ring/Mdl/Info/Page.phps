<?php
/**
 * @table info_page
 *
 * @field title VARCHAR(255) -edit -show container h1
 * @field content MEDIUMTEXT -edit wysiwyg Libro 650 -check htmlPurify -show
 * 
 * @field topics -has many R_Mdl_Info_Topic -inverse pages
 * 
 * @index title -unique
 */
class R_Mdl_Info_Page extends O_Dao_ActiveRecord {

	public function url($mode="") {
		return O_UrlBuilder::get(($mode?$mode.":":"").urlencode($this->title));
	}
	
	static public function getByUrlName($name) {
		return self::getByTitle(urldecode($name));
	}
	
	static public function getByTitle($title) {
		return O_Dao_Query::get(__CLASS__)->test("title", $title)->getOne();;
	}
	
	
}