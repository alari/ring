<?php
/**
 * @table libro_text
 *
 * @field:config anonce -inverse libro_text
 *
 * @field:config content -required Текст произведения необходим -edit wysiwyg Libro 650
 *
 * @field write_time VARCHAR(255) -title Время написания -edit
 * @field write_place VARCHAR(255) -title Место написания -edit
 *
 * @field collection -relative anonce->collection -edit R_Fr_Sys_Libro_Text::editCycle -title Цикл -check R_Mdl_Site_Collection::checkCreate (без цикла)
 */
class R_Mdl_Sys_Libro_Text extends R_Mdl_Sys_Creative {
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