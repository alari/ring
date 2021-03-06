<?php
/**
 * @table crosspost_services
 *
 * @field crossposts -owns many R_Mdl_Site_Crosspost -inverse service
 * @field site -has one R_Mdl_Site -inverse crosspost_services
 *
 * @field no_comments TINYINT DEFAULT 1
 * @field allow_advs TINYINT DEFAULT 1
 * 
 * @field type ENUM('lj','twitter') DEFAULT 'lj' -enum lj:LiveJournal.com или другой Atom; twitter:Twitter -title Выберите сервис для кросспостинга
 *
 * @field userpwd VARCHAR(1023) DEFAULT ''
 * @field atomapi VARCHAR(1023) DEFAULT ''
 * @field blog_url VARCHAR(1023) DEFAULT ''
 */
class R_Mdl_Site_Crosspost_Service extends O_Dao_ActiveRecord {
	
	const TYPE_LJ = "lj";
	const TYPE_TWITTER = "twitter";
	
	public function __construct(R_Mdl_Site $site, $blog_url, $user, $pwd, $type = self::TYPE_LJ, $no_comments = 1, $allow_advs = 1) {
		if (! $site || (! $blog_url && $type == self::TYPE_LJ) || ! $user || ! $pwd) {
			return false;
		}
		
		if ($type == self::TYPE_TWITTER) {
			$blog_url = "http://twitter.com/" . $user;
		}
		
		if (strpos ( $blog_url, "http://" ) !== 0) {
			$blog_url = "http://" . $blog_url;
		}
		
		$this->site = $site;
		$this->blog_url = $blog_url;
		$this->type = $type;
		
		if ($type == self::TYPE_LJ) {
			$dom = new DOMDocument ( );
			if (! @$dom->loadHTMLFile ( $blog_url )) {
				return false;
			}
			$atomapi = "";
			foreach ( $dom->getElementsByTagName ( "link" ) as $link ) {
				if ($link->getAttribute ( "rel" ) == "service.post" && $link->getAttribute ( "type" ) == "application/atom+xml") {
					$atomapi = $link->getAttribute ( "href" );
					break;
				}
			}
			if (! $atomapi) {
				return false;
			}
			$this->atomapi = $atomapi;
		}
		
		$this->userpwd = "$user:$pwd";
		$this->no_comments = $no_comments;
		$this->allow_advs = $allow_advs;
		
		parent::__construct ();
	}
}