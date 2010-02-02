<?php
/**
 * @table site_css_rules
 *
 * @field site -has one R_Mdl_Site -inverse css_rules
 * @field selector TINYTEXT
 * @field rule TINYTEXT
 * @field image ENUM('png','gif','jpeg','-') -image src: imgSrc full; filepath: imgPath full; width: 1600; height: 1600; -edit -title Картинка -required-new Укажите файл картинки
 */
class R_Mdl_Site_CssRule extends O_Dao_ActiveRecord {

	public function __construct() {
		;
	}

	public function getCssLine() {
		$rule = $this->rule;
		if(strpos($rule, '$img')) $rule = str_replace('$img', $this->image, $rule);
		return $this->selector.":".$rule.";";
	}

	static public function updateCssFile(R_Mdl_Site $site) {
		;
	}

	static public function deleteHandler(O_Dao_ActiveRecord $deletedItem) {
		;
	}

	public function imgSrc( $type )
	{
		//return $this->anonce->getFilesUrl() . $this->anonce->id . $type . "." . $this[ "img_full" ];
	}

	public function imgPath( $type, $ext = null )
	{
		//return $this->anonce->getFilesDir() . $this->anonce->id . $type . ($ext ? $ext : "." . $this[ "img_full" ]);
	}

}