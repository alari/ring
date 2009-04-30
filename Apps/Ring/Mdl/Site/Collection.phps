<?php
/**
 * @table collections
 * @field system -has one R_Mdl_Site_System -inverse collections
 *
 * @field title VARCHAR(64) -edit -required -title Название цикла
 * @field description VARCHAR(255) -edit -title Описание или расшифровка
 * @field year VARCHAR(255) -edit -title Год завершения или период работы
 * @field position int NOT NULL DEFAULT 0
 *
 * @field anonces -has owns R_Mdl_Site_Anonce -inverse collection
 *
 * @field info -owns one R_Mdl_Site_CollectionInfo -inverse collection
 *
 * @field content -relative info->content -show-def -edit wysiwyg -check htmlPurify -title Развёрнутое описание в свободной форме
 *
 * @field time INT
 *
 * @index system,position
 */
class R_Mdl_Site_Collection extends O_Dao_ActiveRecord {
	public function __construct(R_Mdl_Site_System $system) {
		parent::__construct();
		$this->time = time();
		$this->info = new R_Mdl_Site_CollectionInfo();
		$this->system = $system;
		$this->position = count($system->collections)+1;
		$this->save();
	}

	public function save() {
		parent::save();
		if($this->info) $this->info->save();
	}

	static public function checkCreate(O_Dao_Renderer_Check_Params $params) {
		$new_value = $params->newValue();
		$new_title = O_Registry::get("app/env/params/collection_new");

		if(!$new_title && $new_value instanceof self) return true;

		$system = O_Registry::get("app/current/system");

		if(!$new_title) $new_title = $params->params();
		if($new_title) {
			$new_value = $system->collections->test("title", $new_title)->getOne();
			if(!$new_value) $new_value = new self($system);
			$new_value->title = $new_title;
			$new_value->save();
			$params->setNewValue($new_value);
			return true;
		}
		throw new O_Dao_Renderer_Check_Exception("Collection is required.");
	}



}