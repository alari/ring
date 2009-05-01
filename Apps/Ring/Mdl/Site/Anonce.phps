<?php
/**
 * @table anonces -loop-full:callback R_Fr_Site_Anonce::showFullQuery -loop:callback R_Fr_Site_Anonce::showQuery -show:callback R_Fr_Site_Anonce::showSelf
 *
 * @field site -has one R_Mdl_Site -inverse anonces -preload
 * @field owner -has one R_Mdl_User -inverse anonces -preload -show
 *
 * @field creative -one-of blog_post; im_picture; libro_text; sound_track
 * @field blog_post -owns one R_Mdl_Blog_Post -inverse anonce
 * @field im_picture -owns one R_Mdl_Im_Picture -inverse anonce
 * @field libro_text -owns one R_Mdl_Libro_Text -inverse anonce
 * @field sound_track -owns one R_Mdl_Sound_Track -inverse anonce
 *
 * @field collection -has one R_Mdl_Site_Collection -inverse anonces
 * @field position INT DEFAULT 0
 *
 * @field system -has one R_Mdl_Site_System -inverse anonces -preload
 * @field tags -has many R_Mdl_Site_Tag -inverse anonces
 *
 * @field linked -has many R_Mdl_Site_Anonce -inverse linked
 *
 * @field access ENUM('public','protected','private','disable') NOT NULL DEFAULT 'disable' -enum public: Всем; protected: Друзьям и друзьям друзей; private: Друзьям; disable: Только себе
 * @field time INT -show date
 * @field title VARCHAR(255) -show linkInContainer
 * @field description TEXT -show
 *
 * @index time
 * @index system,time
 * @index collection,position
 * @index position
 */
class R_Mdl_Site_Anonce extends O_Dao_NestedSet_Root {
	const NODES_CLASS = "R_Mdl_Site_Comment";

	public function __construct( R_Mdl_Site_Creative $creative, R_Mdl_Site_SysInstance $instance )
	{
		parent::__construct();
		$this->creative = $creative;
		$this->system = $instance->system;
		$this->site = $instance->system->site;
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
	public function url()
	{
		$field = O_Dao_TableInfo::get( __CLASS__ )->getFieldInfo( "creative" )->getRealField( $this );
		return $this->system->creativeUrl( $this[ $field ] );
	}

	public function link() {
		return "<a href=\"".$this->url()."\">".$this->title."</a>";
	}

	public function isVisible() {
		return true;
	}


	public function getFilesDir() {
		$dir = $this->site->staticPath("f");
		if(!is_dir($dir)) mkdir($dir);
		$dir .= "/".substr($this->id, 0, 1);
		if(!is_dir($dir)) mkdir($dir);
		$dir .= "/".substr($this->id, 1);
		if(substr($dir, -1) == "/") $dir .= "x";
		if(!is_dir($dir)) mkdir($dir);
		$dir .= "/";
		return $dir;
	}

	public function getFilesUrl() {
		$dir = $this->site->staticUrl("f");
		$dir .= "/".substr($this->id, 0, 1);
		$dir .= "/".substr($this->id, 1);
		if(substr($dir, -1) == "/") $dir .= "x";
		$dir .= "/";
		return $dir;
	}



}