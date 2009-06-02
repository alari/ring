<?php
class R_Mr_Tpl_Home extends R_Template {
	public function displayContents()
	{
		$page = R_Mdl_Info_Page::getByTitle("Заглавная страница");
		if($page) $page->show($this->layout());
		$this->layout()->setTitle();
	}
	
	public function displayRightColumn() {
		?><p><b>Информация о проекте:</b></p><?
		O_Dao_Query::get("R_Mdl_Info_Topic")->show($this->layout());
	}
	

}