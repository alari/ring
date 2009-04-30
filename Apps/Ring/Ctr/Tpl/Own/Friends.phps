<?php
class R_Ctr_Tpl_Own_Friends extends R_Ctr_Template {
	public $friends;

	public function displayContents()
	{
		?>
		<h1>Ваши друзья</h1>
<ul>
		<?
		foreach ($this->friends as $friend)
			echo "<li>", $friend->link(), "</li>";
		?>
		</ul>

<form method="POST">
<fieldset><legend>Добавить в друзья</legend> OpenId пользователя: <input
	type="text" name="friend_openid" value="" /> <input type="Submit"
	value="Добавить" /></fieldset>
</form>
<?
	}

}