<?php

class R_Fr_Afisha_Bill extends R_Fr_Site_Creative {

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
<div class="date"><?=($post->event_time>time()?"Случится":"Случилось")?>: <?=date("d.m.Y H:i", $post->event_time)?></div>

<h1><?=$post->anonce->title?></h1>
<?if($post->anonce->description){?><h5><?=$post->anonce->description?></h5><?}?>

<?if($post["img_full"] && $post["img_full"]!="-"){?>
<div class="img"><a href="<?=$post->img_full?>"><img src="<?=$post->img_preview?>" alt="<?=htmlspecialchars($post->anonce->title." - ".$post->anonce->description)?>"/></a></div>
<?}?>

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
<div class="date">Добавлено: <?=date("d.m.Y H:i:s", $post->time)?></div>
<div class="date"><?=($post->event_time>time()?"Случится":"Случилось")?>: <?=date("d.m.Y H:i", $post->event_time)?></div>
<?if(count($tags)){
$was_tag=0;
	?>
<div class="tags">Теги: <?foreach($tags as $t){if($was_tag) echo ", "; else $was_tag=1; echo $t->link($post->system);}?></div>
<?}?>
</div>

<h2><a href="<?=$post->url()?>"><?=$post->anonce->title?></a></h2>
<?if($post->anonce->description){?><h5><?=$post->anonce->description?></h5><?}?>

<?if($post["img_full"] && $post["img_full"] != "-"){?>
<div class="img"><a href="<?=$post->url()?>"><img src="<?=$post->img_preview?>" alt="<?=htmlspecialchars($post->anonce->title." - ".$post->anonce->description)?>"/></a></div>
<?}?>

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
<div class="anonce bill">
<div class="cvr"><strong><?=
				$record->link()?> / <?=date("d.m.Y", $record->time)?></strong>
<div class="cnt">
<?if($record->description){?><?=$record->description?><?}?>
</div>
</div>
</div>
<?
	}


	/**
	 * Shows rss contents
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showRssCont(O_Dao_Renderer_Show_Params $params ) {
		$post = $params->record();
		?>
		<div class="img"><a href="<?=$post->link()?>"><img src="<?=$post->img_preview?>" alt="<?=htmlspecialchars($post->anonce->title." - ".$post->anonce->description)?>"/></a></div>
		<?
		echo $params->record()->content;
	}
}