<?php

class R_Fr_Sys_Im_Picture extends R_Fr_Sys_Creative {
	static public function editGallery(O_Dao_Renderer_Edit_Params $params) {
		O_Dao_Renderer_Edit_Callbacks::selectRelation($params);
		echo "<br/>";
		?>
<div class="oo-renderer-field"><div class="oo-renderer-title">Новая галерея:</div>
<input class="text" type="text" name="collection_new" value=""/></div>
		<?
	}

	/**
	 * Shows picture on its own page
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

<h1><?=$post->anonce->title?></h1>

<div class="img"><a href="<?=$post->img_full?>"><img src="<?=$post->img_preview?>" alt="<?=htmlspecialchars($post->anonce->title." - ".$post->anonce->description)?>"/></a></div>

<div class="content"><?=$post->content?></div>

<?
	}

	/**
	 * Shows picture in a loop -- on tag or friends page
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

	/**
	 * Shows anonce on frontpage or in relations
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showAnonce(O_Dao_Renderer_Show_Params $params) {
		$record = $params->record();
		?>
<div class="anonce im">
<div class="cvr"><strong><?=
				$record->link()?></strong>
<div class="cnt"><a href="<?=
				$record->url()?>"><img src="<?=
				$record->creative->img_tiny?>"
	alt="<?=
				htmlspecialchars( $record->title . " - " . $record->description )?>" /></a></div>
</div>
</div>
<?
	}

	/**
	 * Shows rss contents
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showRssContCallback(O_Dao_Renderer_Show_Params $params ) {
		$post = $params->record();
		?>
		<div class="img"><a href="<?=$post->link()?>"><img src="<?=$post->img_preview?>" alt="<?=htmlspecialchars($post->anonce->title." - ".$post->anonce->description)?>"/></a></div>
		<?
		echo $params->record()->content;
	}

	static public function showAtomPostCallback(O_Dao_Renderer_Show_Params $params) {
		self::showRssContCallback($params);
		echo "<hr/>";
		$url = $params->record()->url();
		echo "[ <a href=\"$url\">".$params->record()->title."</a> ]";
	}

}