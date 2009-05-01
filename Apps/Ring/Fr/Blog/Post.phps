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
<div class="prop-ava">
<?=$post->owner->link()?><br/>
<?=$post->owner->avatar()?>
</div>

<h2><a href="<?=$post->url()?>"><?=$post->anonce->title?></a></h2>

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
<?=$post->owner->link()?><br/>
<?=$post->owner->avatar()?>
</div>

<div class="date">Добавлена: <?=date("d.m.Y H:i:s", $post->time)?></div>

<?if(count($tags)){
$was_tag=0;
	?>
<div class="tags">Теги: <?foreach($tags as $t){if($was_tag) echo ", "; else $was_tag=1; echo $t->link($post->system);}?></div>
<?}?>
</div>
<h2><a href="<?=$post->url()?>"><?=$post->anonce->title?></a></h2>

<div class="content"><?=$post->content?></div>

<div class="comms"><a href="<?=$post->url()?>">Комментариев: <?=$post->anonce->nodes->getFunc()?></a></div>

</div>
<?
	}
}