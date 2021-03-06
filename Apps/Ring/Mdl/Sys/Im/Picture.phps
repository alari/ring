<?php
/**
 * @table sys_im_picture
 *
 * @field:config anonce -inverse im_picture
 *
 * @field description -relative anonce->description -edit -title Кр. опис.
 * @field collection -relative anonce->collection -edit R_Fr_Sys_Im_Picture::editGallery -title Галерея -check R_Mdl_Site_Collection::checkCreate
 *
 * @field img_full ENUM('png','gif','jpeg','-') -image src: imgSrc full; filepath: imgPath full; width: 1600; height: 1600; cascade: img_preview, img_loop, img_tiny -edit -title Картинка -required-new Укажите файл картинки
 * @field img_preview -image src: imgSrc preview; filepath: imgPath preview; width: 700; height: 700
 * @field img_loop -image src: imgSrc loop; filepath: imgPath loop; width: 400; height: 400
 * @field img_tiny -image src: imgSrc tiny; filepath: imgPath tiny; width: 150; height: 120
 *
 * @field:replace anonce,img_tiny
 */
class R_Mdl_Sys_Im_Picture extends R_Mdl_Sys_Creative {

	public function save()
	{
		parent::save();
		if (!$this->title) {
			$this->title = "Картинка";
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