<?php
/**
 * @table sound_track
 *
 * @field:config anonce -inverse sound_track
 * @field collection -relative anonce->collection -edit -title Альбом
 */
class R_Mdl_Sound_Track extends R_Mdl_Site_Creative {
	const HAS_COLLECTIONS = 1;

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