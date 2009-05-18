<?php

class R_Fr_Im_Picture {
	static public function editGallery(O_Dao_Renderer_Edit_Params $params) {
		O_Dao_Renderer_Edit_Callbacks::selectRelation($params);
		echo "<br/>";
		?>
<div>Новая галерея: <input type="text" name="collection_new" value=""/></div>
		<?
	}

	/**
	 * Shows picture on its own page
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

<h2><a href="<?=$post->url()?>"><?=$post->anonce->title?></a></h2>

<div class="img"><a href="<?=$post->img_full?>"><img src="<?=$post->img_preview?>" alt="<?=htmlspecialchars($post->anonce->title." - ".$post->anonce->description)?>"/></a></div>

<div class="content"><?=$post->content?></div>

<?
	}

	/**
	 * Shows picture in a loop -- on tag or friends page
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
<div class="date">Добавлена: <?=date("d.m.Y H:i:s", $post->time)?></div>
<?if(count($tags)){
$was_tag=0;
	?>
<div class="tags">Теги: <?foreach($tags as $t){if($was_tag) echo ", "; else $was_tag=1; echo $t->link($post->system);}?></div>
<?}?>
<div class="date">Галерея: <?=$post->collection->link()?></div>
</div>

<h2><a href="<?=$post->url()?>"><?=$post->anonce->title?></a></h2>

<div class="img"><a href="<?=$post->url()?>"><img src="<?=$post->img_loop?>" alt="<?=htmlspecialchars($post->anonce->title." - ".$post->anonce->description)?>"/></a></div>

<div class="content"><?=$post->content?></div>

<div class="comms"><a href="<?=$post->url()?>">Комментариев: <?=$post->anonce->nodes->getFunc()?></a></div>

</div>
<?
	}
}