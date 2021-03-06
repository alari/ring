<?php
/**
 * @table sys_blog_post
 *
 * @field:config anonce -inverse blog_post
 *
 * @field:config content -required Текст записи необходим -edit wysiwyg Libro 550
 */
class R_Mdl_Sys_Blog_Post extends R_Mdl_Sys_Creative {

	public function save()
	{
		parent::save();
		if (!$this->anonce) {
			return true;
		}
		$split_content = strip_tags( $this->content );
		
		$title = $this->title ? $this->title : substr( $split_content, 0, 32 );
		
		$this->anonce->description = substr( $split_content, 0, 255 );
		$this->anonce->title = $title;
		$this->anonce->save();
		return true;
	}

}