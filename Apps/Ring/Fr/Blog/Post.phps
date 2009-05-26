<?php

class R_Fr_Blog_Post {
	/**
	 * Shows blog post on its own page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showDefCallback(O_Dao_Renderer_Show_Params $params) {
		$post = $params->record();
?>
<div class="prop-ava">
<?=$post->owner->link()?><br/>
<?=$post->owner->avatar()?>
</div>

<?if($post->title){?><h1><?=$post->title?></h1><?}?>
<?if($post->time){?><div class="post_date"><?=date('d.m.Y, H:i',$post->time)?></div><?}?>

<div class="content"><?=$post->content?></div>
<?
	}

	static public function showRssCont(O_Dao_Renderer_Show_Params $params ) {
		echo $params->record()->content;
	}


	/**
	 * Shows blog post in a loop -- on tag or blog page
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
<?=$post->owner->link()?><br/>
<?=$post->owner->avatar()?>
</div>

<div class="date">Пост добавлен: <?=date("d.m.Y H:i:s", $post->time)?></div>

<?if(count($tags)){
$was_tag=0;
	?>
<div class="tags">Теги: <?foreach($tags as $t){if($was_tag) echo ", "; else $was_tag=1; echo $t->link($post->system);}?></div>
<?}?>
</div>
<?if($post->title){?><h2><a href="<?=$post->url()?>"><?=$post->title?></a></h2><?}?>

<div class="content"><?=$post->content?></div>

<div class="comms"><a href="<?=$post->url()?>">Комментариев: <?=$post->anonce->nodes->getFunc()?></a></div>

</div>
<?
	}
}