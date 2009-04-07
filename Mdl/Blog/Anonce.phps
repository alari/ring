<?php
/**
 * @table blog_anonce
 * @field post -owns one R_Mdl_Blog_Post -inverse anonce
 * @field blog -has one R_Mdl_Blog -inverse anonces
 * @field tags -has many R_Mdl_Tag -inverse blog_anonces
 *
 * @field time INT
 * @field title VARCHAR(255)
 * @field description TEXT
 * @field access ENUM('public','protected','private','disable')
 *
 * @index time,blog
 */
class R_Mdl_Blog_Anonce extends O_Dao_ActiveRecord {

	public function url()
	{
		return $this->blog->system->url( $this[ "post" ] );
	}

}