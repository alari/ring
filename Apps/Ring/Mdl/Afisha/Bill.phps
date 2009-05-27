<?php
/**
 * @table im_picture
 *
 * @field:config anonce -inverse afisha_bill
 * @field:config title -required Введите название события
 *
 * @field event_time -relative anonce->time -edit timestamp -check timestamp -title Время события
 *
 * @field description -relative anonce->description -edit -title Кр. опис.
 *
 * @field img_full ENUM('png','gif','jpeg','-') -image src: imgSrc full; filepath: imgPath full; width: 1280; height: 1280; cascade: img_preview, img_tiny -edit -title Картинка
 * @field img_preview -image src: imgSrc preview; filepath: imgPath preview; width: 500; height: 500
 * @field img_tiny -image src: imgSrc tiny; filepath: imgPath tiny; width: 100; height: 90
 *
 * @field:replace anonce,img_tiny
 */
class R_Mdl_Afisha_Bill extends R_Mdl_Site_Creative {
	public function save()
	{
		parent::save();
		if(!$this->title) {
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

	public function imgSrc($type) {
		return $this->anonce->getFilesUrl().$this->anonce->id.$type.".".$this["img_full"];
	}

	public function imgPath($type, $ext=null) {
		return $this->anonce->getFilesDir().$this->anonce->id.$type.($ext?$ext:".".$this["img_full"]);
	}



}