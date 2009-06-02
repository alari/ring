<?php
/**
 * @table info_topic -loop:envelop container ul
 *
 * @field title VARCHAR(255) -edit -show-def linkInContainer h1 -show-loop linkInContainer li -required Введите!
 * 
 * @field pages -has many R_Mdl_Info_Page -inverse topics
 * 
 * @index title -unique
 */
class R_Mdl_Info_Topic extends O_Dao_ActiveRecord {

	public function url() {
		return O_UrlBuilder::get("topic:".urlencode($this->title));
	}
	
	static public function getByUrlName($name) {
		return self::getByTitle(urldecode($name));
	}
	
	static public function getByTitle($title) {
		return O_Dao_Query::get(__CLASS__)->test("title", $title)->getOne();;
	}
	
}