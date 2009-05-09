<?php
abstract class R_Lf_Template extends R_Template {
	protected $layoutClass = "R_Lf_Layout";
	public $site = false;

	public function prepareMeta(){
		$description = Array();
		$keywords = Array();

		if($this->getSite()->owner) {
			$description[] = "���� ������: ".$this->site->owner->nickname;
			$keywords[] = "�����";
			$keywords[] = $this->site->owner->nickname;
		}

		$description[] = "���� &laquo;".$this->getSite()->title."&raquo;";

		$description[] = "������ � ������ ���������� ������ Mirari.Name";

		$this->layout()->setMetaDescription($description);
		$this->layout()->setMetaKeywords($keywords);
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
				throw new O_Ex_Redirect( "http://" . O_Registry::get( "app/hosts/project" ) . "/" );
		}
		return $this->site;
	}

}