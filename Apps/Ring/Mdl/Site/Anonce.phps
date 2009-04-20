<?php
/**
 * @table anonces
 *
 * @field site -has one R_Mdl_Site -inverse anonces -preload
 * @field owner -has one R_Mdl_User -inverse anonces -preload -show
 *
 * @field creative -one-of blog_post, im_picture
 * @field blog_post -owns one R_Mdl_Blog_Post -inverse anonce
 * @field im_picture -owns one R_Mdl_Im_Picture -inverse anonce
 *
 * @field system -has one R_Mdl_Site_System -inverse anonces -preload
 * @field tags -has many R_Mdl_Site_Tag -inverse anonces
 *
 * @field access ENUM('public','protected','private','disable') NOT NULL DEFAULT 'disable'
 * @field time INT -show date
 * @field title VARCHAR(255) -show linkInContainer
 * @field description TEXT -show
 *
 * @index time
 * @index system,time
 */
class R_Mdl_Site_Anonce extends O_Dao_NestedSet_Root {
	const NODES_CLASS = "R_Mdl_Site_Comment";

	public function __construct(R_Mdl_Site_Creative $creative, R_Mdl_Site_SysInstance $instance) {
		parent::__construct();
		$this->creative = $creative;
		$this->system = $instance->system;
		$this->time = $creative->time;
		$this->access = $instance->system->access;
		$this->owner = R_Mdl_Session::getUser();
		$this->save();
	}

	/**
	 * Returns url of main content page
	 *
	 * @return string
	 */
	public function url() {
		$field = O_Dao_TableInfo::get(__CLASS__)->getFieldInfo("creative")->getRealField($this);
		return $this->system->creativeUrl($this[$field]);
	}
}