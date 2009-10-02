<?php
class R_Lf_Sys_Tpl_Crosspost extends R_Lf_Sys_Template {
	public $crossposts;
	public $available_services;

	public function displayContents()
	{
		foreach ($this->crossposts as $c) echo $c->url;
		echo "<hr/>";
		foreach($this->available_services as $s) echo $s->blog_url;
	?>
<p><b>Кросспостинг</b> – это возможность создания идентичных записей в разных местах одновременно. Иными словами, добавляя запись в Кольце, вы можете автоматически создать ее на других сайтах. Подробнее смотрите <a href="http://mirari.name/Кросспостинг" target="_blank">здесь</a>.</p>
<?php
	}

}