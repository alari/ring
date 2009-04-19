<?php
/**
 * @table im_picture
 *
 * @field:config anonce -inverse im_picture
 *
 */
class R_Mdl_Im_Picture extends R_Mdl_Site_Creative {
	const NODES_CLASS = "R_Mdl_Im_Comment";

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