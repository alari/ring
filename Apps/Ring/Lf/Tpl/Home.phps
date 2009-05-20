<?php
class R_Lf_Tpl_Home extends R_Lf_Template {

	public function displayContents()
	{
		?>
		<h1><?=$this->site->title?></h1>
		<?
		foreach($this->getSite()->getSystems() as $system) {
			$system->show($this->layout(), "home");
		}

		$this->layout()->addHeadLink("alternate", $this->getSite()->url("rss"), "application/rss+xml", "RSS");
	}

	public function displayNav()
	{
		if ($this->getSite()->owner) {
?>
<center>
<?=$this->getSite()->owner->link()."<br/>".$this->getSite()->owner->avatar(1)?>
</center>
<?
		}
		$tags = $this->getSite()->tags->limit(100);
		R_Fr_Site_Tag::showCloud($tags);
		?>
	<br/>
<p><a href="<?=$this->getSite()->url("comments")?>">Комментарии на сайте</a></p>
		<?
	}

}