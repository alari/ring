<?php
class R_Lf_Sys_Tpl_Crosspost extends R_Lf_Sys_Template {
	public $crossposts;
	public $available_services;

	public function displayContents()
	{
		$this->layout()->setTitle( "Кросспосты: " . $this->creative->anonce->title );
		?><h1>Кросспосты: <?=$this->creative->anonce->link()?></h1><?
		if (count( $this->crossposts )) {
			?>
<h2>Текущие</h2>
<?
			foreach ($this->crossposts as $c) {
				?>

<div>
<p>Блог: <b><a href="<?=$c->service->blog_url?>"><?=$c->service->blog_url?></a></b></p>
	<?
				if ($c->url) {
					?><p>Страница кросспоста: <i><a href="<?=$c->url?>"><?=$c->url?></a></i></p><?
				}
				?>
	<?
				if ($c->crossposted) {
					?><p>Последнее изменение: <?=date( "d.m.Y H:i:s", $c->crossposted )?></p><?
				}
				?>
	<?
				if (!$c->crossposted || $c->last_update > $c->crossposted) {
					?><p><i>В
очереди на изменение</i></p><?
				}
				?>
	<p align="right"><small><a href="?d=<?=$c->id?>"
	onclick="return confirm('Удалить кросспост из блога?')">Удалить</a></small></p>
</div>
<?
			}
		}
		if (count( $this->available_services )) {
			?>
<h2>Доступные блоги</h2>
<?
			foreach ($this->available_services as $s) {
				?>

<div>
<p>Блог: <b><a href="<?=$s->blog_url?>"><?=$s->blog_url?></a></b>
&ndash; <a href="?a=<?=$s->id?>"
	onclick="return confirm('Кросспостить в этот блог?')">Совершить кросспост</a></p>
</div>
<?
			}
		}
		echo "<hr/>";
		?>
<p><b>Кросспостинг</b> – это возможность создания идентичных записей в
разных местах одновременно. Иными словами, добавляя запись в Кольце, вы
можете автоматически создать ее на других сайтах. Подробнее смотрите <a
	href="http://mirari.name/Кросспостинг" target="_blank">здесь</a>.</p>
<?php
	}

}