<?php
class R_Lf_Sys_Cmd_RssFeed extends R_Lf_Sys_Command {

	public function process()
	{
		$query = $this->instance->system->anonces;
		R_Mdl_Session::setQueryAccesses($query, $this->getSite());
		$query->orderBy("time DESC")->limit(15);
		echo '<?xml version="1.0" encoding="utf-8"?>';
		?>
<rss version="2.0">
  <channel>
    <title><?=$this->instance->system->title?></title>
    <link><?=$this->instance->url()?></link>
    <lastBuildDate><?=gmdate("D, d M Y H:i:s")?> GMT</lastBuildDate>
    <generator>Mirari.Name via Orena.org</generator>
		<?
    foreach($query as $a) $a->show(null, "rss");
    echo "</channel></rss>";
	}

	public function isAuthenticated()
	{
		return $this->instance && $this->can( "read " . $this->instance->system[ "access" ], $this->getSite() );
	}

}