<?php

class R_Fr_Sys_Libro_Text extends R_Fr_Sys_Creative {
	static public function editCycle(O_Dao_Renderer_Edit_Params $params) {
		O_Dao_Renderer_Edit_Callbacks::selectRelation($params);
		?>
<div class="oo-renderer-field"><div class="oo-renderer-title">Новый цикл:</div>
<input class="text" type="text" name="collection_new" value=""/></div>
		<?
	}

	/**
	 * Shows text on its own page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showDefCallback(O_Dao_Renderer_Show_Params $params) {
		$post = $params->record();
?>
<div class="prop-ava">
<?=$post->owner->link()?>
<?=$post->owner->avatar()?>
</div>

<h1><?=$post->title?></h1>

<div class="content"><?=$post->content?></div>

<?if($post->write_time || $post->write_place) {?>
<div class="time-place">
<?
	if($post->write_time) echo 'Написано: '.$post->write_time.($post->write_place?", ":"");
	echo $post->write_place;?>
</div>
<?}?>
<div class="time-place">Добавлено: <?=date("Y.m.d", $post->time)?></div>

<?
	}

	/**
	 * Shows text as announce in a loop -- on tag or friends page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showFullCallback(O_Dao_Renderer_Show_Params $params) {
		$post = $params->record();
		$tags = $post->tags;
?>
<div class="post">
<div class="prop">
<div class="prop-ava">
<?=$post->owner->link()?>
<?=$post->owner->avatar()?>
</div>
<div class="date">Добавлено: <?=date("d.m.Y H:i:s", $post->time)?></div>
<?if(count($tags)){
$was_tag=0;
	?>
<div class="tags">Теги: <?foreach($tags as $t){if($was_tag) echo ", "; else $was_tag=1; echo $t->link($post->system);}?></div>
<?}?>
<div class="date">Цикл: <?=$post->collection->link()?></div>
</div>

<h2><a href="<?=$post->url()?>"><?=$post->anonce->title?></a></h2>

<div class="comms"><a href="<?=$post->url()?>">Комментариев: <?=$post->anonce->nodes->getFunc()?></a></div>

</div>
<?
	}

	/**
	 * Shows anonce on frontpage or in relations
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showAnonce(O_Dao_Renderer_Show_Params $params) {
		$record = $params->record();
		?>
<div class="anonce libro">
<div class="cvr"><strong><?=
				$record->link()?></strong>
<div class="cnt">(<?=
				$record->collection->link()?>)</div>
</div>
</div>
<?
	}

}