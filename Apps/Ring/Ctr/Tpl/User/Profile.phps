<?php
class R_Ctr_Tpl_User_Profile extends R_Ctr_Template {

	public $user;

	public function displayContents()
	{
		?>

		<h1>Профиль пользователя: <?=$this->user->link()?></h1>
		<?if($this->user->identity){?>OpenId: <?=$this->user->identity;}?>
		<br/><br/>

		<center>
<?=$this->user->avatar( 1 )?>
</center>

		<?

	}

}