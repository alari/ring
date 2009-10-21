<?php
class R_Lf_Cmd_TmpCss extends R_Lf_Command {

	public function process()
	{
		header("Content-type: text/css");
		if(!isset($_SESSION["c"]) || !is_array($_SESSION["c"])) {
			$styleScheme = $this->getSite()->style_scheme;
			if($styleScheme) echo $styleScheme->getCssText();
			return;
		}
		$c = $_SESSION["c"];
echo R_Mdl_Site_StyleScheme::tmpCssFromArray($c);
	}

}