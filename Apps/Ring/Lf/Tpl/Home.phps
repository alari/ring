<?php
class R_Lf_Tpl_Home extends R_Lf_Template {

	public function displayContents()
	{
		foreach($this->getSite()->getSystems() as $system) {
			$system->show($this->layout(), "home");
		}
	}

	public function displayNav()
	{
		if ($this->getSite()->owner) {
			echo $this->getSite()->owner->avatar(1);
		}
		$tags = $this->getSite()->tags->limit(100);
		R_Fr_Site_Tag::showCloud($tags);
	}

}