<?php

class R_Fr_Sound_Track {
	static public function editAlbum(O_Dao_Renderer_Edit_Params $params) {
		O_Dao_Renderer_Edit_Callbacks::selectRelation($params);
		echo "<br/>";
		?>
<div>Новый альбом: <input type="text" name="collection_new" value=""/></div>
		<?
	}

	/**
	 * Shows blog post on its own page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showSelf(O_Dao_Renderer_Show_Params $params) {
		$post = $params->record();
?>
<div class="prop-ava">
<?=$post->owner->link()?>
<?=$post->owner->avatar()?>
</div>

<h2><a href="<?=$post->url()?>"><?=$post->title?></a></h2>

<br/><br/>

<div class="track-listen">
<object type="application/x-shockwave-flash" data="<?=$params->layout()->staticUrl("swf/player_mp3_maxi.swf")?>" width="400" height="20">
    <param name="movie" value="<?=$params->layout()->staticUrl("swf/player_mp3_maxi.swf")?>" />
    <param name="bgcolor" value="#ffffff" />
    <param name="FlashVars" value="mp3=<?=htmlspecialchars($post->file)?>&amp;loop=1&amp;showvolume=1&amp;showinfo=1&amp;volumewidth=40&amp;width=400" />
</object>
</div>

<br/>
<div class="track-props">
<p>Ссылка на файл: <a href="<?=$post->file?>"><?=$post->title?></a></p>
<p>Длительность: <?=floor($post->duration/60).":".($post->duration%60)?></p>
<p>Битрейт: <?=round($post->bitrate/1000)?> Кбит/сек</p>
</div>

<div class="content"><?=$post->content?></div>

<?
	}

	/**
	 * Shows blog post in a loop -- on tag or blog page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showInLoop(O_Dao_Renderer_Show_Params $params) {
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
</div>

<br/><br/>

<center>
<h2><a href="<?=$post->url()?>"><?=$post->anonce->title?></a></h2>
</center>

<br/><br/>

<div class="track-listen">
<object type="application/x-shockwave-flash" data="<?=$params->layout()->staticUrl("swf/player_mp3_maxi.swf")?>" width="400" height="20">
    <param name="movie" value="<?=$params->layout()->staticUrl("swf/player_mp3_maxi.swf")?>" />
    <param name="bgcolor" value="#ffffff" />
    <param name="FlashVars" value="mp3=<?=htmlspecialchars($post->file)?>&amp;loop=0&amp;showvolume=1&amp;showinfo=1&amp;volumewidth=40&amp;width=400" />
</object>
</div>

<br/>
<div class="track-props">
<p>Длительность: <?=floor($post->duration/60).":".($post->duration%60)?></p>
<p>Битрейт: <?=round($post->bitrate/1000)?> Кбит/сек</p>
</div>

<br/><br/>

<div class="comms"><a href="<?=$post->url()?>">Комментариев: <?=$post->anonce->nodes->getFunc()?></a></div>

</div>
<?
	}
}