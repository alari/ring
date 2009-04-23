<?php

class R_Fr_Blog_Post {
	/**
	 * Shows blog post on its own page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showSelf(O_Dao_Renderer_Show_Params $params) {
		$post = $params->record();
?>
<span class="prop-ava" style="float:right">
<?=$post->owner->link()?><br/>
<?=$post->owner->avatar()?>
</span>
<h2><a href="<?=$post->url()?>"><?=$post->anonce->title?></a></h2>

<?=$post->content?>
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
<div class="post" style="margin:5px;border:1px solid orange">
<div class="prop" style="color:gray">
<span class="prop-ava" style="float:right">
<?=$post->owner->link()?><br/>
<?=$post->owner->avatar()?>
</span>
Добавлена: <?=date("d.m.Y H:i:s", $post->time)?>
<?if(count($tags)){
$was_tag=0;
	?>
<br/>Теги: <?foreach($tags as $t){if($was_tag) echo ", "; else $was_tag=1; echo $t->link($post->system);}?>
<?}?>
</div>
<h2><a href="<?=$post->url()?>"><?=$post->anonce->title?></a></h2>

<?=$post->content?>
<div style="text-align:right"><a href="<?=$post->url()?>">Комментариев: <?=$post->anonce->nodes->getFunc()?></a></div>
</div>
<?
	}
}