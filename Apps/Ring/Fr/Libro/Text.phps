<?php

class R_Fr_Libro_Text {
	static public function editCycle(O_Dao_Renderer_Edit_Params $params) {
		O_Dao_Renderer_Edit_Callbacks::selectRelation($params);
		echo "<br/>";
		?>
<div>Новый цикл: <input type="text" name="collection_new" value=""/></div>
		<?
	}

	/**
	 * Shows text on its own page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showDef(O_Dao_Renderer_Show_Params $params) {
		$post = $params->record();
?>
<div class="prop-ava">
<?=$post->owner->link()?>
<?=$post->owner->avatar()?>
</div>

<h2><a href="<?=$post->url()?>"><?=$post->title?></a></h2>

<div class="content"><?=$post->content?></div>

<?if($post->write_time || $post->write_place) {?>
<div style="text-align:right;font-style:italic">
<?
	if($post->write_time) echo $post->write_time.($post->write_place?"<br/>":"");
	echo $post->write_place;?>
</div>
<?}?>

<?
	}

	/**
	 * Shows text as announce in a loop -- on tag or friends page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showFullInLoop(O_Dao_Renderer_Show_Params $params) {
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

<br/><br/>

<center>
<h2><a href="<?=$post->url()?>"><?=$post->anonce->title?></a></h2>
</center>

<br/><br/>

<div class="comms"><a href="<?=$post->url()?>">Комментариев: <?=$post->anonce->nodes->getFunc()?></a></div>

</div>
<?
	}
}