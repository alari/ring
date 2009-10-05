<?php
/**
 * @table sys_sound_track
 *
 * @field:config anonce -inverse sound_track
 * @field collection -relative anonce->collection -edit R_Fr_Sys_Sound_Track::editAlbum -title Альбом -check R_Mdl_Site_Collection::checkCreate (без альбома)
 *
 * @field file -file ext_allow:mp3; src:fileSrc; filepath:filePath -edit -title Файл mp3 -required Укажите файл композиции
 * @field duration INT
 * @field bitrate INT
 *
 * @field:replace anonce,file
 */
class R_Mdl_Sys_Sound_Track extends R_Mdl_Sys_Creative {

	public function save()
	{
		parent::save();
		if (!$this->anonce) {
			return true;
		}
		if (!$this->title) {
			$this->title = "Композиция без названия";
			parent::save();
		}
		$split_content = strip_tags( $this->content );
		
		$title = $this->title;
		
		$this->anonce->description = substr( $split_content, 0, 255 );
		$this->anonce->title = $title;
		$this->anonce->save();
		
		if (is_file( $this->filePath() ) && class_exists( "ffmpeg_movie", false )) {
			$movie = new ffmpeg_movie( $this->filePath(), false );
			$this[ "duration" ] = round( $movie->getDuration() );
			$this[ "bitrate" ] = $movie->getBitRate();
			parent::save();
		}
		
		return true;
	}

	public function fileSrc()
	{
		return $this->anonce->getFilesUrl() . $this->id . ".mp3";
	}

	public function filePath()
	{
		return $this->anonce->getFilesDir() . $this->id . ".mp3";
	}

	public function getDuration()
	{
		return floor( $this->duration / 60 ) . ":" . sprintf( "%02d", $this->duration % 60 );
	}

}