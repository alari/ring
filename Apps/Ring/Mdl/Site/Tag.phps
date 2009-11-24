<?php
/**
 * @table tags -edit:submit Сохранить
 * @field site -has one R_Mdl_Site -inverse tags
 *
 * @field title VARCHAR(64) NOT NULL -edit -required -title Название метки
 * @field description VARCHAR(255) -edit -title Описание или расшифровка
 * @field weight int NOT NULL DEFAULT 0
 *
 * @field anonces -has many R_Mdl_Site_Anonce -inverse tags -signal tags -order-by time DESC
 *
 * @index site,weight
 * @index weight
 * @index title
 * @index site,title -unique
 *
 * @registry app/dao-listeners/-/tags/R_Mdl_Site_Tag R_Mdl_Site_Tag::signalHandler -add
 */
class R_Mdl_Site_Tag extends O_Dao_ActiveRecord {
	
	/**
	 * Creates tag -- unique for each site
	 *
	 * @param R_Mdl_Site $site
	 */
	public function __construct(R_Mdl_Site $site) {
		$this ["site"] = $site->id;
		$this->title = '';
		parent::__construct ();
		$this->site = $site;
	}
	
	/**
	 * Returns tag url -- for whole site or its concrete system
	 *
	 * @param string $urlbase
	 * @return string
	 */
	public function url($urlbase = "", $page = 1) {
		return $this->site->url ( ($urlbase ? $urlbase . "/" : "") . "tag/" . ($page > 1 ? $page . "/" : "") . urlencode ( $this->title ) );
	}
	
	/**
	 * Returns link for a tag
	 *
	 * @param R_Mdl_Sys_Instance $sys
	 * @return string
	 */
	public function link(R_Mdl_Sys_Instance $sys = null) {
		return "<a href=\"" . $this->url ( $sys ? $sys->urlbase : "" ) . "\"" . ($this->description ? ' title="' . htmlspecialchars ( $this->description ) . '"' : '') . ">" . $this->title . "</a>";
	}
	
	/**
	 * Tags creation/removal signals handler
	 *
	 * @param O_Dao_ActiveRecord $fieldValue
	 * @param O_Dao_ActiveRecord $object
	 * @param const $event
	 */
	public function signalHandler($fieldValue, O_Dao_ActiveRecord $object, $event) {
		try {
			$object->weight = count ( $object->anonces );
			$object->save ();
		} catch ( PDOException $e ) {
		}
	}

}