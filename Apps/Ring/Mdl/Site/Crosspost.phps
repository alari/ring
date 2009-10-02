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
 */
class R_Mdl_Site_Crosspost extends O_Dao_ActiveRecord {
	public function __construct(R_Mdl_Site_Anonce $anonce, R_Mdl_Site_CrosspostService $serv) {
		$this->anonce = $anonce;
		$this->service = $serv;
		parent::__construct();
	}


	public function post() {
$data = "<?xml version='1.0'?>
    <entry xmlns='http://www.w3.org/2005/Atom'>
      <title>Atom-Powered Robots Run Amok</title>
      <updated>2003-12-13T18:30:02Z</updated>
      <author><name>John Doe</name></author>
      <content>Some text.</content>
    </entry>";
$curl = curl_init($this->service->atomapi);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($curl, CURLOPT_USERPWD, $this->service->userpwd);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_exec($curl);
	}

}