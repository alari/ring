<?php
class R_Mr_Tpl_Page extends R_Template {
	public $page;

	public function displayContents()
	{
		$this->layout()->setTitle( $this->page->title );
		
		$this->page->show( $this->layout() );
	}

	public function displayRightColumn()
	{
		?><p><b>Страница в рубриках:</b></p><?
		$this->page->topics->show( $this->layout() );
		
		if (R_Mdl_Session::isLogged() && R_Mdl_Session::getUser()->isOurUser()) {
			?>
<br />
<br />
<a href="<?=$this->page->url( "edit" )?>">Править страницу</a>
<?
		}
	}

}