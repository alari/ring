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
</fieldset>
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