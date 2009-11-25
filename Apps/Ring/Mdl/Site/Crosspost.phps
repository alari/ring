<?php
/**
 * @table crossposts
 *
 * @field anonce -has one R_Mdl_Site_Anonce -inverse crossposts
 * @field service -has one R_Mdl_Site_Crosspost_Service -inverse crossposts
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
	
	private $logicObject;
	
	public function __construct(R_Mdl_Site_Anonce $anonce, R_Mdl_Site_Crosspost_Service $serv) {
		$this->anonce = $anonce;
		$this->service = $serv;
		parent::__construct ();
	}
	
	/**
	 * Returns logic object to handle crosspost
	 *
	 * @return R_Mdl_Site_Crosspost_Logic
	 */
	protected function getLogic() {
		if (! $this->logicObject) {
			switch ($this->service ["type"]) {
				case R_Mdl_Site_Crosspost_Service::TYPE_LJ :
					$this->logicObject = new R_Mdl_Site_Crosspost_LiveJournalLogic ( );
					break;
				case R_Mdl_Site_Crosspost_Service::TYPE_TWITTER :
					$this->logicObject = new R_Mdl_Site_Crosspost_TwitterLogic ( );
					break;
			}
			
			$this->logicObject->setCrosspost ( $this );
		}
		return $this->logicObject;
	}
	
	public function post() {
		return $this->getLogic ()->post ();
	}
	
	public function update() {
		return $this->getLogic ()->update ();
	}
	
	public function delete() {
		$this->getLogic ()->delete ();
		parent::delete ();
	}
	
	static public function handleQueue() {
		foreach ( O_Dao_Query::get ( __CLASS__ )->test ( "crossposted", 0 ) as $crp ) {
			$crp->post ();
		}
		foreach ( O_Dao_Query::get ( __CLASS__ )->where ( "last_update>crossposted" ) as $crp ) {
			$crp->update ();
		}
	}

}