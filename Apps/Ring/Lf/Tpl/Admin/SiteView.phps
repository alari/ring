<?php
class R_Lf_Tpl_Admin_SiteView extends R_Lf_Template {

	public $css_source;
	public $files = array ();

	public function displayContents()
	{
		?>
<form method="post" enctype="application/x-www-form-urlencoded">
<fieldset><legend>Редактировать CSS-файл сайта</legend> <i>Корень для
статических файлов оформления: <u><?=$this->getSite()->staticUrl( "" )?></u></i>
<center><textarea style="width: 100%; height: 250px" name="css"><?=htmlspecialchars( $this->css_source )?></textarea>
<input type="hidden" name="action" value="css" /> <input type="submit"
	value="Сохранить файл" /></center>
<i><a href="?action=revert"
	onclick="return confirm('Вы уверены? Все ваши изменения будут утеряны.')">Вернуть
стиль по умолчанию</a></i></fieldset>
</form>
<form method="post" enctype="multipart/form-data">
<fieldset><legend>Файлы оформления для сайта</legend>
<table>
	<tr>
		<th>Файл</th>
		<th>Адрес для CSS</th>
		<th>x</th>
	</tr>
<?
		foreach ($this->files as $f) {
			?>
<tr>
		<td><a href="<?=$this->getSite()->staticUrl( $f )?>" target="_blank"><?=$f?></a></td>
		<td><?=$this->site->staticUrl( $f )?></td>
		<td align="center"><a href="?delete=<?=urlencode( $f )?>"
			title="Удалить файл">x</a></td>
	</tr>
<?
		}
		?>
<tr>
		<td colspan="3" align="right"><input type="file" name="f" /> <input
			type="submit" value="Закачать файл" /> <br />
		Можно закачивать только картинки в .gif, .png, .jpg, до 120кб <br />
		Название файла должно состоять из цифр и латинских букв</td>
	</tr>
</table>
</fieldset>
<input type="hidden" name="action" value="file" /></form>

<form method="post" enctype="multipart/form-data">
<fieldset><legend>Иконка сайта</legend>
<p>Иконка отображается в заголовке браузера, индексируется поисковыми
системами, существует для красоты. Должна быть в формате .ico</p>
<center><input type="file" name="f" /> <input type="submit"
	value="Обновить иконку" /></center>
</fieldset>
<input type="hidden" name="action" value="favicon" /></form>

<br />
<hr />
<ul>
<?
		foreach (O_Dao_Query::get( "R_Mdl_Site_StyleScheme" ) as $s) {
			?>
<li style="background-color:<?=$s->color_back?>;color:<?=$s->color_text?>"><a href="?set-scheme=<?=$s->id?>" style="<?=$s->color_text?>"><?=$s->title?></a></li>
<?
		}
		?>
		<li><a href="?set-scheme=null">Очистить схему</a></li>
</ul>
<br />
<hr />
<form method="post">
<fieldset><legend>(beta) попробовать базовые цвета</legend>
<?
		$c = array ();
		if (isset( $_SESSION[ "c" ] ) && is_array( $_SESSION[ "c" ] ))
			$c = $_SESSION[ "c" ];
		?><ul>
<?
		for ($i = 1; $i <= 10; $i++) {
			?>
<li>Цвет №<?=$i?>: <input type="text" name="c[<?=$i?>]"
		value="<?=(isset( $c[ $i ] ) ? $c[ $i ] : "")?>" onkeyup="$('span-color-<?=$i?>').setStyle('background', this.value)" /> <span id="span-color-<?=$i?>" style="width:10px;height:20px;background:<?=(isset( $c[ $i ] ) ? $c[ $i ] : "")?>">&nbsp;</span></li>
<?
		}
		?>
</ul>
<br />
<input type="submit" value="Попробовать" /> <br />
<br />
Название: <input type="text" name="scheme-title" /> <input type="submit"
	name="save-scheme" value="Сохранить схему" /> <input type="hidden"
	name="action" value="style-scheme" /></fieldset>
</form>
<?
	}

	public function displayNav()
	{
		?>
<ul>
	<li><a href="<?=$this->url( "Admin/Site" )?>">Настройки сайта</a></li>
	<li><a href="<?=$this->url( "Admin/SiteView" )?>">Редактировать
	оформление</a></li>
</ul>
<?
	}

}

?>