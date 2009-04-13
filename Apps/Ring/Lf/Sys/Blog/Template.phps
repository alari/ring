<?php
abstract class R_Lf_Sys_Blog_Template extends R_Lf_Template {
	public $site;
	public $can_write;
	public $tags;
	public $blog;
	public $post;

	public function displayNav()
	{
		?>
<p><b><?=$this->blog->link()?></b></p>
		<?
		if ($this->can_write) {
			?>
<ul>
<li><a href="<?=$this->blog->system->url( "form" )?>">Добавить запись</a></li>
<?

			if ($this->post) {
				?>
	<li><a href="<?=$this->blog->system->url( "form/" . $this->post->id )?>">Править
	запись</a></li>
<?
			}

			?></ul><?
		}

		if ($this->tags) {
?><p><b>Метки</b></p>
<ul>
<?
			foreach ($this->post->tags as $tag){
				echo "<li>", $tag->link($this->blog->system), "</li>";}
				echo "</ul>";
		}
	}
}