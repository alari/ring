<?php
/**
 * @field anonce -owns one R_Mdl_Site_Anonce -preload
 *
 * @field time INT
 * @field title VARCHAR(255) -edit -show linkInContainer h1 -title Название
 * @field content MEDIUMTEXT -show -edit wysiwyg -check htmlPurify -title
 *
 * @field owner -relative anonce->owner
 * @field tags -relative anonce->tags -edit R_Fr_Site_Tag::editList -title Метки (тэги)
 * @field system -relative anonce->system
 * @field access -relative anonce->access -edit enum -enum public: Всем; protected: Друзьям и друзьям друзей; private: Друзьям; disable: Только себе -title Доступ
 * @field flags -relative anonce->flags -enum 0: Всем; 3: Друзьям и друзьям друзей; 1: Друзьям; 32: Только себе -edit enum -title Доступ (*)
 *
 * @field nodes -relative anonce->nodes
 */
abstract class R_Mdl_Site_Creative extends O_Dao_ActiveRecord {
	public function __construct( R_Mdl_Sys_Implementation $instance )
	{
		$this[ "time" ] = time();
		parent::__construct();
		new R_Mdl_Site_Anonce( $this, $instance );
	}

	/**
	 * Returns url for creative page
	 *
	 * @return string
	 */
	public function url()
	{
		return $this->anonce->url();
	}
}