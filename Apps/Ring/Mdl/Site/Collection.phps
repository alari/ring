<?php
/**
 * @table collections -edit:submit Сохранить изменения
 * @field system -has one R_Mdl_Sys_Instance -inverse collections
 *
 * @field title VARCHAR(64) -edit -required -title Название -show-loop linkInContainer h2 -show-def container h1
 * @field description VARCHAR(255) -edit -title Описание или расшифровка -show-loop container h3 -show-def container h2
 * @field year VARCHAR(255) -edit -title Год завершения или период работы -show
 * @field position int NOT NULL DEFAULT 0
 *
 * @field anonces -has many R_Mdl_Site_Anonce -inverse collection -show loop -order-by position
 *
 * @field content MEDIUMTEXT -show-def -edit wysiwyg Libro -check HtmlPurifier -title Развёрнутое описание в свободной форме
 *
 * @field time INT
 *
 * @index system,position
 */
class R_Mdl_Site_Collection extends O_Dao_ActiveRecord {

	public function __construct(R_Mdl_Sys_Instance $system) {
		parent::__construct ();
		$this->time = time ();
		$this->system = $system;
		$this->position = count ( $system->collections ) + 1;
		$this->save ();

		$this->createResource();
	}

	public function createResource() {
		if($this->getResource()) return;
		$res = new R_Mdl_Resource($this->system->site);
		$this->system->getResource()->injectBottom($res);
		$res->reload();
		$res->type = R_Mdl_Resource::TYPE_COLLECTION;
		$res->groups = 3;
		$res->show_to_followers = 0;
		$res->setContent($this);
		$this->syncRes($res);
	}

	private function syncRes(R_Mdl_Resource $res=null) {
		if(!$res) $res = $this->getResource();
		if(!$res) return;
		$sys = $this->system->getResource();
		$res->title = $this->title;
		$res->url_part = "coll-".$this->id;
		$res->url_cache = $sys->url_cache."/coll-".$this->id;

		$res->anonymous_access = $sys->anonymous_access;
		$res->logged_access = $sys->logged_access;
		$res->groups_access = $sys->groups_access;
		$res->time = $this->time;

		$res->save();
	}

	/**
	 * @return R_Mdl_Resource
	 */
	public function getResource() {
		return $this->system->site->nodes->test("content", $this->id)->test("content_class", __CLASS__)->getOne();
	}

	public function save() {
		parent::save ();
		$this->syncRes();
	}

	public function delete() {
		$this->getResource()->delete();
		parent::delete();
	}

	static public function checkCreate(O_Form_Check_AutoProducer $producer) {
		$new_value = $producer->getValue ();
		$new_title = O_Registry::get ( "env/params/collection_new" );

		if (! $new_title && $new_value instanceof self)
			return true;

		$system = O_Registry::get ( "app/current/system" );

		if (! $new_title)
			$new_title = $producer->getParams ();
		if ($new_title) {
			$new_value = $system->collections->test ( "title", $new_title )->getOne ();
			if (! $new_value)
				$new_value = new self ( $system );
			$new_value->title = $new_title;
			$new_value->save ();
			$producer->setValue ( $new_value );
			return true;
		}
		throw new O_Form_Check_Error ( "Collection is required." );
	}

	public function link() {
		return "<a href=\"" . $this->url () . "\">" . $this->title . "</a>";
	}

	public function url() {
		return $this->system->url ( "coll-" . $this->id );
	}

	/**
	 * Sets collection position
	 *
	 * @param int $newPosition
	 */
	public function setPosition($newPosition) {
		if ($newPosition == $this->position)
			return;
		if ($newPosition <= 0 || $newPosition > count ( $this->system->collections ) + 1)
			return;

		$colls = $this->system->collections;

		if ($newPosition > $this->position) {
			$colls->test ( "position", $this->position, ">" )->test ( "position", $newPosition, "<=" )->field ( "position", "position-1", 1 )->update ();
		} else {
			$colls->test ( "position", $this->position, "<" )->test ( "position", $newPosition, ">=" )->field ( "position", "position+1", 1 )->update ();
		}

		$this->position = $newPosition;
		parent::save ();

		$res = $this->getResource();
		$prev = $this->system->collections->test("position", $newPosition, "<")->clearOrders()->orderBy("position DESC")->getOne();
		if($prev) $prev->injectAfter($res);
		else $this->system->getResource()->injectTop($res);
	}
}