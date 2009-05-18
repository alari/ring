<?php
class R_Lf_Cmd_RssFeed extends R_Lf_Command {

	public function process()
	{
		$query = $this->getSite()->anonces;
		R_Mdl_Session::setQueryAccesses($query, $this->getSite());
		$query->orderBy("time DESC")->limit(20);


		echo '<?xml version="1.0" encoding="utf-8"?>';
		?>
<rss version="2.0">
  <channel>
    <title><?=$this->getSite()->title?></title>
    <link><?=$this->getSite()->url()?></link>
    <lastBuildDate><?=gmdate("D, d M Y H:i:s")?> GMT</lastBuildDate>
    <generator>Mirari.Name via Orena.org</generator>
		<?
    foreach($query as $a) $a->show(null, "rss");
    echo "</channel></rss>";
	}

}