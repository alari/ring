<?php

class R_Mdl_Site_Crosspost_TwitterLogic extends R_Mdl_Site_Crosspost_Logic {
	public function delete() {
		if ($this->crosspost && $this->crosspost->postid) {
			$this->getTwitter ()->destroyStatus ( $this->crosspost->postid );
		}
	}

	public function update() {
		if ($this->crosspost) {
			$this->crosspost->crossposted = time ();

		}
		return $this->error ( "Can't update twitter satus." );
	}

	public function post() {
		$status = $this->crosspost->anonce->title;
		$len = 139 - strlen ( $this->crosspost->anonce->url () );
		if (iconv_strlen ( $status ) > $len) {
			$status = iconv_substr ( $status, 0, $len - 3 ) . "...";
		}
		$status .= " " . $this->crosspost->anonce->url ();
		$r = json_decode ( $this->getTwitter ()->updateStatus ( $status, null, "json" ) );
		if (! is_object ( $r ) || ! $r->id)
			return $this->error ( "Wrong json response: " . print_r ( $r, 1 ) );
		$this->crosspost->postid = $id = $r->id;
		$this->crosspost->url = $this->crosspost->service->blog_url . "/status/" . $id;
		$this->crosspost->crossposted = time ();

		return $this->crosspost->save ();
	}

	/**
	 * Returns twitterlib object
	 *
	 * @return Twitter
	 */
	private function getTwitter() {
		if (! $this->crosspost)
			return false;
		list ( $usr, $pwd ) = explode ( ":", $this->crosspost->service->userpwd, 2 );
		return new Twitter ( $usr, $pwd );
	}

}