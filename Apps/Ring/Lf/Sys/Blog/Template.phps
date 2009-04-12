<?php
abstract class R_Lf_Sys_Blog_Template extends R_Lf_Template {
	public $site;
	public $can_write;
	public $tags;
	public $blog;
	public $post;

	public function displayNav() {
		if($this->can_write) {
?>
<p><b><a href="<?=$this->blog->system->url("form")?>">Добавить запись</a></b></p>
<?
		}
		echo "tags<br/>";
	}
}