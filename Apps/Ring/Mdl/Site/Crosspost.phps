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

	private function prepareEntity( $showId = false )
	{
		if (!$this->anonce->isVisible())
			return false;

		ob_start();
		$this->anonce->creative->show( null, "atom-post" );
		$data = ob_get_clean();
		$title = $this->anonce->title;
		$url = $this->anonce->url();
		$published = $this->anonce->time;
		$updated = $this->last_update ? $this->last_update : $this->anonce->time;
		$id = $showId ? $this->postid : null;

		return O_Feed_AtomPub::prepareEntry( $title, $url, $published, $data, $updated, $id );

	}

	public function post()
	{
		$ret = O_Feed_AtomPub::post( $this->service->atomapi, $this->prepareData(),
				$this->service->userpwd );

		if (!is_array( $ret ))
			return $this->error( O_Feed_AtomPub::getError() );

		$this->postid = $ret[ "id" ];
		$this->url = $ret[ "post_id" ];
		$this->edit_url = $ret[ "edit_url" ];
		$this->crossposted = time();

		return $this->save();
	}

	public function update()
	{
		$ret = O_Feed_AtomPub::update( $this->edit_url, $this->prepareData( 1 ),
				$this->service->userpwd );

		if (!$ret) {
			return $this->error( O_Feed_AtomPub::getError() );
		}

		$this->crossposted = time();
		return $this->save();
	}

	private function error( $msg )
	{
		$this->error_msg = $msg;
		$this->error_time = time();
		$this->save();
		return false;
	}

	public function delete()
	{
		if ($this->edit_url)
			O_Feed_AtomPub::delete( $this->edit_url, $this->service->userpwd );
		parent::delete();
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