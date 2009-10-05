<?php
class R_Lf_Sys_Tpl_Linked extends R_Lf_Sys_Template {
	public $linked;

	public function displayContents()
	{
		if (count( $this->linked )) {
			echo "<ul>";
			foreach ($this->linked as $linked) {
				?>
<li><?=$linked->link()?><?=($linked->owner == $this->creative->anonce->owner ? "" : " - <i>" . $linked->owner->link() . "</i>")?> &nbsp; <small><a
	href="?remove=<?=$linked->id?>">Удалить связь</a></small></li>
<?
			}
			echo "</ul>";
		}
		?>
<form method="post">
<fieldset><legend>Добавить связь</legend>
<p>Вы можете связать практически что угодно, размещённое на Вашем сайте,
с практически чем угодно на Вашем или другом сайте Кольца. Например, к
стихотворению так можно привязать песню, к фотографии &ndash; запись в
блоге и т.п.</p>
<p>Введите внутренний ID того, к чему хотите устроить связь: <input
	type="text" size="8" name="link_target" /> <input type="submit"
	value="Связать" /></p>
</fieldset>
</form>

<?
	}

}