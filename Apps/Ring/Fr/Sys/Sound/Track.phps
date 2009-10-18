<?php

class R_Fr_Sys_Sound_Track extends R_Fr_Sys_Creative {

	static public function editAlbum( O_Dao_Renderer_Edit_Params $params )
	{
		if($params instanceof O_Dao_Renderer_Edit_Params){
			O_Dao_Renderer_Edit_Callbacks::selectRelation( $params );
		} else {
			$bl = new O_Form_Row_Select($params->getFieldName());
			$bl->autoProduce($params);
			$bl->render();
		}
		?>
<div class="oo-renderer-field">
<div class="oo-renderer-title">Новый альбом:</div>
<input class="text" type="text" name="collection_new" value="" /></div>
<?
	}

	/**
	 * Shows soundtrack on its own page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showDefCallback( O_Dao_Renderer_Show_Params $params )
	{
		$post = $params->record();
		?>
<div class="prop-ava">
<?=$post->owner->link()?>
<?=$post->owner->avatar()?>
</div>

<h1><?=$post->title?></h1>

<br />
<br />

<div class="track-listen"><object type="application/x-shockwave-flash"
	data="<?=$params->layout()->staticUrl( "swf/player_mp3_maxi.swf" )?>"
	width="400" height="20">
	<param name="movie"
		value="<?=$params->layout()->staticUrl( "swf/player_mp3_maxi.swf" )?>" />
	<param name="bgcolor" value="#ffffff" />
	<param name="FlashVars"
		value="mp3=<?=htmlspecialchars( $post->file )?>&amp;loop=1&amp;showvolume=1&amp;volumewidth=40&amp;width=400" />
</object></div>

<br />
<div class="track-props">
<p>Ссылка на файл: <a href="<?=$post->file?>"><?=$post->title?></a></p>
<p>Длительность: <?=$post->getDuration()?></p>
<p>Битрейт: <?=round( $post->bitrate / 1000 )?> Кбит/сек</p>
</div>

<div class="content"><?=$post->content?></div>

<?
	}

	/**
	 * Shows blog post in a loop -- on tag or blog page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showFullCallback( O_Dao_Renderer_Show_Params $params )
	{
		$post = $params->record();
		$tags = $post->tags;
		?>
<div class="post">
<div class="prop">
<div class="prop-ava">
<?=$post->owner->link()?>
<?=$post->owner->avatar()?>
</div>
<div class="date">Добавлено: <?=date( "d.m.Y H:i:s", $post->time )?></div>
<?
		if (count( $tags )) {
			$was_tag = 0;
			?>
<div class="tags">Теги: <?
			foreach ($tags as $t) {
				if ($was_tag)
					echo ", ";
				else
					$was_tag = 1;
				echo $t->link( $post->system );
			}
			?></div>
<?
		}
		?>
<div class="date">Альбом: <?=$post->collection->link()?></div>
</div>

<br />

<h2><a href="<?=$post->url()?>"><?=$post->anonce->title?></a></h2>

<br />

<div class="track-listen"><object type="application/x-shockwave-flash"
	data="<?=$params->layout()->staticUrl( "swf/player_mp3_maxi.swf" )?>"
	width="400" height="20">
	<param name="movie"
		value="<?=$params->layout()->staticUrl( "swf/player_mp3_maxi.swf" )?>" />
	<param name="bgcolor" value="#ffffff" />
	<param name="FlashVars"
		value="mp3=<?=htmlspecialchars( $post->file )?>&amp;loop=0&amp;showvolume=1&amp;volumewidth=40&amp;width=400" />
</object></div>

<br />
<div class="track-props">
<p>Длительность: <?=$post->getDuration()?></p>
<p>Битрейт: <?=round( $post->bitrate / 1000 )?> Кбит/сек</p>
</div>
<div class="comms"><a href="<?=$post->url()?>">Комментариев: <?=$post->anonce->nodes->getFunc()?></a></div>

</div>
<?
	}

	/**
	 * Shows anonce on frontpage or in relations
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showAnonce( O_Dao_Renderer_Show_Params $params )
	{
		$record = $params->record();
		?>
<div class="anonce sound">
<div class="cvr"><strong><?=$record->link()?></strong>
<div class="cnt">(<?=$record->collection->link()?>)</div>
</div>
</div>
<?
	}

	static public function showRssContCallback( O_Dao_Renderer_Show_Params $params )
	{
		$post = $params->record();
		?><div class="track-listen"><object type="application/x-shockwave-flash"
	data="<?=$params->layout()->staticUrl( "swf/player_mp3_maxi.swf" )?>"
	width="400" height="20">
	<param name="movie"
		value="<?=$params->layout()->staticUrl( "swf/player_mp3_maxi.swf" )?>" />
	<param name="bgcolor" value="#ffffff" />
	<param name="FlashVars"
		value="mp3=<?=htmlspecialchars( $post->file )?>&amp;loop=0&amp;showvolume=1&amp;volumewidth=40&amp;width=400" />
</object></div>

<br />
<div class="track-props">
<p>Длительность: <?=$post->getDuration()?></p>
<p>Битрейт: <?=round( $post->bitrate / 1000 )?> Кбит/сек</p>
<p>Альбом: <?=$post->collection->link()?></p>
</div>
<?
	}

}