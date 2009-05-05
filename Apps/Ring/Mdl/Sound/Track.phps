<?php
/**
 * @table sound_track
 *
 * @field:config anonce -inverse sound_track
 * @field collection -relative anonce->collection -edit -title Альбом
 *
 * @field file -file ext_allow:mp3;
 * @field length INT
 * @field bitrate INT
 */
class R_Mdl_Sound_Track extends R_Mdl_Site_Creative {
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

		if(is_file($this->filePath()) && class_exists("", false)) {

		}

		return true;
	}


	public function fileSrc() {
		return $this->anonce->getFilesUrl().$this->id.".mp3";
	}

	public function filePath() {
		return $this->anonce->getFilesDir().$this->id.".mp3";
	}

}