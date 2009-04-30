<?php
/**
 * @table libro_text
 *
 * @field:config anonce -inverse libro_text
 *
 * @field:config content -required Текст произведения необходим
 * @field collection -relative anonce->collection -edit -title Цикл
 */
class R_Mdl_Libro_Text extends R_Mdl_Site_Creative {
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