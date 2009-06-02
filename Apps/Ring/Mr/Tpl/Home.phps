<?php
class R_Mr_Tpl_Home extends R_Template {
	public function displayContents()
	{
		$page = R_Mdl_Info_Page::getByTitle("Заглавная страница");
?>
<table>
<tr><td><?($page ? $page->show($this->layout()) : "")?></td><td><?O_Dao_Query::get("R_Mdl_Info_Topic")->show($this->layout());?></td></tr>
</table>
<?
	}

}