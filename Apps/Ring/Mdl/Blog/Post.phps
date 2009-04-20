<?php
/**
 * @table blog_post
 *
 * @field:config anonce -inverse blog_post
 *
 * @field content MEDIUMTEXT -show -edit wysiwyg -required Текст записи необходим -check htmlPurify -title
 *
 * @field:replace content,tags
 *
 */
class R_Mdl_Blog_Post extends R_Mdl_Site_Creative {

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


	public function url()
	{
		return $this->anonce->url();
	}

}