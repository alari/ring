<?php
/**
 * @table crosspost_services
 *
 * @field crossposts -owns many R_Mdl_Site_Crosspost -inverse service
 * @field site -has one R_Mdl_Site -inverse crosspost_services
 *
 * @field userpwd VARCHAR(1023) DEFAULT ''
 * @field atomapi VARCHAR(1023) DEFAULT ''
 * @field blog_url VARCHAR(1023) DEFAULT ''
 */
class R_Mdl_Site_CrosspostService extends O_Dao_ActiveRecord {
	public function __construct(R_Mdl_Site $site, $blog_url, $user, $pwd) {
		if(!$site || !$blog_url || !$user || !$pwd) {
			$_SESSION["notice"] = "need more fields";
			return false;
		}

		if(strpos($blog_url, "http://") !== 0) $blog_url = "http://".$blog_url;

		$this->blog_url = $blog_url;
		$this->site = $site;

		$dom = new DOMDocument();
		if(!@$dom->loadHTMLFile($blog_url)) {
			$_SESSION["notice"] = "Can't load $blog_url html";
			return false;
		}
		$atomapi = "";
		foreach($dom->getElementsByTagName("link") as $link) {
			if($link->getAttribute("rel") == "service.post" && $link->getAttribute("type") == "application/atom+xml") {
				$atomapi = $link->getAttribute("href");
				break;
			}
		}
		if(!$atomapi) {
			$_SESSION["notice"] .= "AtomApi not found";
			return false;
		}
		$this->atomapi = $atomapi;
		$this->userpwd = "$user:$pwd";

		parent::__construct();
	}
}