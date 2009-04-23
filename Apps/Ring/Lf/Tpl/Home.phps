<?php
class R_Lf_Tpl_Home extends R_Lf_Template {

	public function displayContents()
	{
		?>
so homepage there
<?
		foreach($this->getSite()->getSystems() as $system) {
			$system->show($this->layout(), "home");
		}
	}

	public function displayNav()
	{
		if ($this->getSite()->owner) {
			?>
<img src="<?=$this->site->owner->avatarUrl( 1 )?>"
	alt="<?=htmlspecialchars( $this->site->owner->nickname )?>" />
<?
		}
		$tags = $this->getSite()->tags;
		R_Fr_Site_Tag::showCloud($tags);
	}

}