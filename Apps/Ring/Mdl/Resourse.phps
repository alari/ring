<?php
/**
 * @table site_resources
 *
 * @field url_part VARCHAR(12) DEFAULT NULL
 * @field url_cache VARCHAR(255) DEFAULT NULL
 *
 * @field title VARCHAR(255)
 *
 * @field type TINYINT NOT NULL DEFAULT 0
 * @field content INT
 * @field content_class VARCHAR(255)
 * @field units_type CHAR(6) -enum blog:Блог; im:Изображения; sound:Музыка; libro:Литература; afisha:Афиша
 *
 * @index root,url_cache(24) -unique
 * @index content,content_class
 */
class R_Mdl_Resource extends R_Mdl_Resource_Acl {
	const ROOT_CLASS = "R_Mdl_Site";
	const NODES_CLASS = "R_Mdl_Comment";

	const TYPE_SYSTEM = 1;
	const TYPE_COLLECTION = 2;
	const TYPE_ANONCE = 3;
	const TYPE_SITE = 4;

	public function getPageTitle() {
		;
	}

	public function setContent(O_Dao_ActiveRecord $record=null) {
		if(!$record) {
			$this->content = 0;
			$this->content_class = "";
		} else {
			$this->content = $record["id"];
			$this->content_class = get_class($record);
		}
	}

	public function getContent() {
		if(!$this->content || !$this->content_class) return null;
		return O_Dao_ActiveRecord::getById($this->content, $this->content_class);
	}

}