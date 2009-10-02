<?php
/**
 * @table crossposts
 *
 * @field anonce -has one R_Mdl_Site_Anonce -inverse crossposts
 * @field service -has one R_Mdl_Site_CrosspostService -inverse crossposts
 *
 * @field crossposted INT DEFAULT 0
 * @field url VARCHAR(511) DEFAULT ''
 * @field postid VARCHAR(1023) DEFAULT ''
 * @field edit_url VARCHAR(1023) DEFAULT ''
 *
 * @field last_update INT DEFAULT 0
 *
 * @field error_msg VARCHAR(1023)
 * @field error_time INT
 */
class R_Mdl_Site_Crosspost extends O_Dao_ActiveRecord {

	public function __construct( R_Mdl_Site_Anonce $anonce, R_Mdl_Site_CrosspostService $serv )
	{
		$this->anonce = $anonce;
		$this->service = $serv;
		parent::__construct();
	}

	private function prepareData( $showId = false )
	{
		if (!$this->anonce->isVisible())
			return false;
		ob_start();
		$this->anonce->creative->show( null, "atom-post" );
		$descr = ob_get_clean();
		$date = date( "Y-m-d", $this->anonce->time ) . "T" . date( "H:i:s", $this->anonce->time );
		$updated = $this->last_update ? $this->last_update : $this->anonce->time;
		$updated = date( "Y-m-d", $updated ) . "T" . date( "H:i:s", $updated );

		ob_start();
		echo "<?xml version='1.0' encoding='utf-8'?>";
		?>
<entry xmlns='http://www.w3.org/2005/Atom'>
<title><?=htmlspecialchars( $this->anonce->title )?></title>
<?
		if ($showId) {
			?><id><?=$this->postid?></id><?
		}
		?>
<link rel="alternate" type="text/html" href="<?=$this->anonce->url()?>" />
<published><?=$date?></published>
<updated><?=$updated?></updated>
<author>
<name><?=$this->anonce->owner->nickname?></name>
<uri><?=$this->anonce->owner->url()?></uri>
</author>
<content type="html">
<?=htmlspecialchars( str_replace(array("\r","\n"), array("",""), $descr) )?>
</content>
</entry>
<?
		return ob_get_clean();
	}

	public function post()
	{
		$ret = O_Feed_AtomPub::post($this->service->atomapi, $this->prepareData(), $this->service->userpwd);

		if(!is_array($ret)) return $this->error(O_Feed_AtomPub::getError());

		$this->postid = $ret["id"];
		$this->url = $ret["post_id"];
				$this->edit_url = $ret["edit_url"];
		$this->crossposted = time();

		return $this->save();
	}

	public function update()
	{
		$data = $this->prepareData( true );
		$f = tmpfile();
		fwrite( $f, $data );
		fseek( $f, 0 );

		$curl = curl_init( $this->service->atomapi );
		curl_setopt( $curl, CURLOPT_PUT, true );
		curl_setopt( $curl, CURLOPT_INFILE, $f );
		curl_setopt( $curl, CURLOPT_INFILESIZE, strlen( $data ) );

		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST );
		curl_setopt( $curl, CURLOPT_USERPWD, $this->service->userpwd );

		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		$ret = curl_exec( $curl );

		if (!$ret)
			return $this->error( curl_error( $curl ) );

		if ($ret) {
			$this->crossposted = time();
			echo $ret;
			return $this->save();
		}
		return false;
	}

	private function error( $msg )
	{
		$this->error_msg = $msg;
		$this->error_time = time();
		$this->save();
		return false;
	}

	static public function handleQueue()
	{
		foreach (O_Dao_Query::get( __CLASS__ )->test( "crossposted", 0 ) as $crp) {
			$crp->post();
		}
		foreach (O_Dao_Query::get( __CLASS__ )->where( "last_update>crossposted" ) as $crp) {
			$crp->update();
		}
	}

}