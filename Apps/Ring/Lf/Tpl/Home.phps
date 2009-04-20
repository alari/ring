<?php
class R_Lf_Tpl_Home extends R_Lf_Template {

	public function displayContents()
	{
		?>
so homepage there
<?
	}

	public function displayNav()
	{
		if ($this->getSite()->owner) {
			?>
<img src="<?=$this->site->owner->avatarUrl( 1 )?>"
	alt="<?=htmlspecialchars( $this->site->owner->nickname )?>" />
<?
		}
	}

}