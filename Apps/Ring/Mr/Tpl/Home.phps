<?php
class R_Mr_Tpl_Home extends R_Template {
	public $page;
	
	public function displayContents()
	{
		$this->page = R_Mdl_Info_Page::getByTitle("Заглавная страница");
		if($this->page) echo $this->page->content;
		$this->layout()->setTitle();
	}
	
	public function displayRightColumn() {
		?><p><b>Информация о проекте:</b></p><?
		O_Dao_Query::get("R_Mdl_Info_Topic")->show($this->layout());
		
		if(R_Mdl_Session::isLogged() && R_Mdl_Session::getUser()->isOurUser()) {
		?>
		<br/><br/><a href="<?=$this->page->url("edit")?>">Править страницу</a>
		<?
		}
	}
	

}