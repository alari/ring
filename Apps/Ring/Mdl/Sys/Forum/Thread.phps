<?php
/**
 * @table sys_forum_thread
 *
 * @field:config anonce -inverse forum_thread
 * @field:config title -required Введите название события
 *
 * @field description -relative anonce->description -edit -title Кр. опис.
 */
class R_Mdl_Sys_Forum_Thread extends R_Mdl_Sys_Creative {

	public function save()
	{
		parent::save();
		if (!$this->title) {
			$this->title = "Событие";
			parent::save();
		}
		if (!$this->anonce) {
			return true;
		}
		
		$this->anonce->title = $this->title;
		$this->anonce->save();
		return true;
	}

	public function imgSrc( $type )
	{
		return $this->anonce->getFilesUrl() . $this->anonce->id . $type . "." . $this[ "img_full" ];
	}

	public function imgPath( $type, $ext = null )
	{
		return $this->anonce->getFilesDir() . $this->anonce->id . $type . ($ext ? $ext : "." . $this[ "img_full" ]);
	}

}