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

	private function prepareData($showId = false) {
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
<?if($showId){?><id><?=$this->postid?></id><?}?>
<link rel="alternate" type="text/html" href="<?=$this->anonce->url()?>" />
<published><?=$date?></published>
<updated><?=$updated?></updated>
<author>
<name><?=$this->anonce->owner->nickname?></name>
<uri><?=$this->anonce->owner->url()?></uri>
</author>
<content type="html">
<?=htmlspecialchars( $descr )?>
</content>
</entry>
<?
		return ob_get_clean();
	}


	public function post()
	{
		$data = $this->prepareData();
		if(!$data) return;

	$curl = curl_init( $this->service->atomapi );
		curl_setopt( $curl, CURLOPT_POST, true );
		curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
		curl_setopt( $curl, CURLOPT_USERPWD, $this->service->userpwd );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		$ret = curl_exec( $curl );
		if (!$ret) {
			return $this->error( curl_error( $curl ) );
		}

		$d = new DOMDocument( );
		if (!$d->loadXml( $ret )) {
			return $this->error( "Cannot load xml data $ret" );
		}
		$this->postid = $d->getElementsByTagName( "id" )->item( 0 )->textContent;
		foreach ($d->getElementsByTagName( "link" ) as $link) {
			if ($link->getAttribute( "rel" ) == "alternate" && $link->getAttribute( "type" ) == "text/html")
				$this->url = $link->getAttribute( "href" );
			if ($link->getAttribute( "rel" ) == "service.edit" && strpos(
					$link->getAttribute( "type" ), "atom+xml" ))
				$this->edit_url = $link->getAttribute( "href" );
		}
		$this->crossposted = time();

		return $this->save();
	}



	public function update() {
		$data = $this->prepareData(true);
		$f = tmpfile();
		fwrite($f, $data);
		fseek($f, 0);

		$curl = curl_init( $this->service->atomapi );
		curl_setopt( $curl, CURLOPT_PUT, true );
		//curl_setopt($curl, CURLOPT_READDATA, $f);

		curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
		curl_setopt( $curl, CURLOPT_USERPWD, $this->service->userpwd );

		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		$ret = curl_exec( $curl );

		if(!$ret) return $this->error(curl_error($curl));

		if($ret) {
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
		foreach(O_Dao_Query::get(__CLASS__)->where("last_update>crossposted") as $crp) {
			$crp->update();
		}
	}

}