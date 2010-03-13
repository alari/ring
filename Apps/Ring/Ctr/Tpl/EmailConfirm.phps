<?php
class R_Ctr_Tpl_EmailConfirm extends R_Ctr_Template {

	public $isSucceed;

	public function displayContents()
	{
		$this->layout()->setTitle("Подтверждение электронной почты.");
		if($this->isSucceed) {
			?>
			<h1>Успешно</h1>
			<p>Вы подтвердили Ваш адрес электронной почты. Спасибо!</p>
			<?
		} else {
			?>
			<h1>Ошибка</h1>
			<p>Устаревшая или неверная ссылка. Подтвердить адрес электронной почты не удалось.</p>
			<?
		}
	}
}