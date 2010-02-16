<?php
class R_Ctr_Tpl_Own_Friends_List extends R_Ctr_Template {

	public $follow;

	public $site;
	public $friends;

	public function displayContents()
	{
		$this->layout()->setTitle( "Ваши друзья - список" );
		?>
<h1>Вы следите за сайтами</h1>
<ul>
<?foreach($this->follow as $site) {?>
<li><?=$site->link().($site->owner ? " - <i>".$site->owner->link()."</i>":"")?></li>
<?}?>
</ul>

<h1>Ваши друзья (сайт: <?=$this->site->link?>)</h1>
<ul>
<?foreach($this->friends as $friend) {?>
<li><?=$friend->link()?></li>
<?}?>
</ul>

<form method="POST">
<fieldset><legend>Следить за автором или сайтом:</legend> OpenId пользователя: <input
	type="text" name="friend_openid" value="" /> <input type="Submit"
	value="Добавить" /></fieldset>
</form>
<?
	}

}