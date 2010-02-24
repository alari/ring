<?php
abstract class R_Lf_Template extends R_Template {
	protected $layoutClass = "R_Lf_Layout";
	public $site = false;

	public function displayNav()
	{
		?>
<p><a href="http://<?=O_Registry::get( "app/hosts/center" )?>/">Центр
Кольца</a></p>
<p><a href="<?=$this->getSite()->url( "comments" )?>">Комментарии на сайте</a></p>
<?
		if($this->getSite()->owner) {
?><p><a href="<?=$this->getSite()->url( "friends.feed" )?>">Друзья автора</a></p><?
		}
	}

	public function prepareMeta()
	{
		$description = Array ();
		$keywords = Array ();

		if ($this->getSite()->owner) {
			$description[] = "Автор: " . $this->site->owner->nickname;
			$keywords[] = "автор";
			$keywords[] = $this->site->owner->nickname;
		}

		$description[] = "Сайт &laquo;" . $this->getSite()->title . "&raquo;";

		$description[] = "Входит в кольцо творческих сайтов Mirari.Name";

		$this->layout()->setMetaDescription( $description );
		$this->layout()->setMetaKeywords( $keywords );
	}

	/**
	 * Returns current site
	 *
	 * @return R_Mdl_Site
	 */
	protected function getSite()
	{
		if ($this->site === false) {
			$this->site = O_Registry::get( "app/current/site" );
			if (!$this->site)
				throw new O_Ex_Redirect(
						"http://" . O_Registry::get( "app/hosts/project" ) . "/" );
		}
		return $this->site;
	}

}