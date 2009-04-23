<?php
abstract class R_Lf_Sys_Template extends R_Lf_Template {
	public $can_write;
	public $tags;
	public $instance;
	public $creative;
	public $tag;

	public function displayNav()
	{
		?>
<p><b><?=$this->instance->link()?></b></p>
<?
		if ($this->can_write) {
			?>
<ul>
	<li><a href="<?=$this->instance->system->url( "form" )?>">Добавить запись</a></li>
<?

			if ($this->creative) {
				?>
	<li><a
		href="<?=$this->instance->system->url( "form/" . $this->creative->id )?>">Править
	запись</a></li>
<?
			}

			?></ul><?
		}

		if ($this->tags) {
			?><p><b>Метки</b></p>
<ul>
<?
			foreach ($this->tags as $tag) {
				echo "<li>", $this->tag == $tag?"<b>":"", $tag->link( $this->instance->system ),$this->tag == $tag?"</b>":"", "</li>";
			}
			echo "</ul>";
		}
	}
}