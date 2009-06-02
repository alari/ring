<?php
class R_Lf_Tpl_Home extends R_Lf_Template {

	public function displayContents()
	{
		?>
		<h1><?=$this->site->title?></h1>
		<div id="sys-sort">
		<?
		foreach($this->getSite()->getSystems() as $system) {
			$system->show($this->layout(), "home");
		}
		echo "</div>";
		
		if(R_Mdl_Session::can("manage site", $this->getSite())) {
		?>
<script type="text/javascript">
R.System.setSortable("#sys-sort", '.system', '<?=$this->getSite()->host?>');
</script>
		<?
		}

		$this->layout()->addHeadLink("alternate", $this->getSite()->url("rss"), "application/rss+xml", "RSS: новое на сайте");
		$this->layout()->addHeadLink("alternate", $this->getSite()->url("atom"), "application/atom+xml", "Atom: новое на сайте");
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
<p><a href="<?=$this->getSite()->url("comments")?>">Комментарии на сайте</a></p>
		<?
	}

}