<?php
abstract class R_Lf_Sys_Template extends R_Lf_Template {
	public $can_write;
	public $can_delete;
	public $tags;
	public $instance;
	public $creative;
	public $tag;

	public function displayNav()
	{
		?>
<p><b><?=$this->instance->system->link()?></b> <small><a href="<?=$this->instance->system->url("comments")?>">Комментарии</a></small></p>
<?
		if ($this->creative) {
			?>
<p><b><?=$this->creative->anonce->link()?></b></p>
<?
			if ($this->creative->anonce->collection) {
				?>
<p><i><?=$this->creative->anonce->collection->link()?></i></p>
<?
			}
		}
		if ($this->can_write) {
			?>
<ul>
	<li><a href="<?=$this->instance->system->url( "form" )?>">Добавить</a></li>
<?

			if ($this->creative) {
				?>
	<li><a
		href="<?=$this->instance->system->url( "form/" . $this->creative->id )?>">Править</a></li>
		<?
				if ($this->can_delete) {
					?>
		<li><a href="?action=delete"
		onclick="return confirm('Вы уверены? Восстановление будет невозможно!')">Удалить</a></li>
<?
				}
				?>

	<li><a
		href="<?=$this->instance->system->url( "linked/" . $this->creative->id )?>">Связи</a></li>

				<?
			}

			?></ul><?
		}

		if ($this->creative) {
			?>

<i>Внутренний ID для связей: <b><?=$this->creative[ "anonce" ]?></b></i>
<?if(R_Mdl_Session::isLogged() && R_Mdl_Session::getUser() != $this->creative->anonce->owner){
	$has_fovarites = R_Mdl_Session::getUser()->favorites->has($this->creative->anonce);
	?>
	<br/>
<i><a href="javascript:void(0)" onclick="new Request({url:'<?=$this->creative->url()?>?action=fav',onComplete:function(){$(this).getParent().set('html', '<b><?=($has_fovarites?"Убрали":"Добавили")?></b>');}}).send();"><?=($has_fovarites?"Убрать из избранного":"Добавить в избранное")?></a></i>
<?}?>

<?
		}

		if ($this->tags instanceof O_Dao_Query && count( $this->tags->getAll() )) {
			?><p><b>Метки</b></p>
<ul>
<?
			foreach ($this->tags as $tag) {
				echo "<li>", $this->tag == $tag ? "<b>" : "", $tag->link( $this->instance->system ), $this->tag == $tag ? "</b>" : "", "</li>";
			}
			echo "</ul>";
		}
	}
}