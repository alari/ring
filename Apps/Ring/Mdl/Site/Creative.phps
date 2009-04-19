<?php
/**
 * @field anonce -owns one R_Mdl_Site_Anonce -preload
 *
 * @field owner -relative anonce->owner
 * @field tags -relative anonce->tags -edit
 * @field system -relative anonce->system
 * @field access -relative anonce->access
 *
 * @field time INT
 * @field title VARCHAR(255) -edit -show linkInContainer h1 -title Название
 */
abstract class R_Mdl_Site_Creative extends O_Dao_NestedSet_Root {
	public function __construct(R_Mdl_Site_SysInstance $instance) {
		$this["time"] = time();
		parent::__construct();
		new R_Mdl_Site_Anonce($this, $instance);
	}

	/**
	 * Returns url for creative page
	 *
	 * @return string
	 */
	public function url() {
		return $this->anonce->url();
	}

}