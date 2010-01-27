<?php
class R_Ctr_Tpl_Own_Friends_List extends R_Ctr_Template {
	public $friends;

	public $follow;

	public function displayContents()
	{
		$this->layout()->setTitle( "Ваши друзья - список" );
		?>
<h1>Ваши друзья</h1>
<ul>
		<?
		foreach ($this->friends as $friend)
			echo "<li>", $friend->link(), " <small><a href=\"?remove=" . $friend->id . "\">Убрать из друзей</a></small></li>";
		?>
		</ul>

<h1>Вы следите за сайтами</h1>
<ul>
<?foreach($this->follows as $site) {?>
<li><?=$site->link()?></li>
<?}?>
</ul>

<form method="POST">
<fieldset><legend>Добавить в друзья</legend> OpenId пользователя: <input
	type="text" name="friend_openid" value="" /> <input type="Submit"
	value="Добавить" /></fieldset>
</form>
<?
	}

}