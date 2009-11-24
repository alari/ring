<?php

class R_Mdl_Site_Crosspost_LiveJournalLogic extends R_Mdl_Site_Crosspost_Logic {
	private function prepareEntity($showId = false) {
		if (! $this->crosspost->anonce->isVisible ())
			return false;
		
		ob_start ();
		$this->crosspost->anonce->creative->show ( null, "atom-post" );
		$data = ob_get_clean ();
		$title = $this->crosspost->anonce->title;
		$url = $this->crosspost->anonce->url ();
		$published = $this->crosspost->anonce->time;
		$updated = $this->crosspost->last_update ? $this->crosspost->last_update : $this->crosspost->anonce->time;
		$id = $showId ? $this->crosspost->postid : null;
		
		return O_Feed_AtomPub::prepareEntry ( $title, $url, $published, $data, $updated, $id, $this->crosspost->service->no_comments );
	
	}
	
	public function delete() {
		if ($this->crosspost && $this->crosspost->edit_url)
			O_Feed_AtomPub::delete ( $this->crosspost->edit_url, $this->crosspost->service->userpwd );
	}
	
	public function update() {
		$ret = O_Feed_AtomPub::update ( $this->crosspost->edit_url, $this->prepareEntity ( 1 ), $this->crosspost->service->userpwd );
		
		if (! $ret) {
			return $this->error ( O_Feed_AtomPub::getError () );
		}
		
		$this->crosspost->crossposted = time ();
		return $this->crosspost->save ();
	}
	
	public function post() {
		$ret = O_Feed_AtomPub::post ( $this->crosspost->service->atomapi, $this->prepareEntity (), $this->crosspost->service->userpwd );
		
		if (! is_array ( $ret ))
			return $this->error ( O_Feed_AtomPub::getError () );
		
		$this->crosspost->postid = $ret ["id"];
		$this->crosspost->url = $ret ["post_url"];
		$this->crosspost->edit_url = $ret ["edit_url"];
		$this->crosspost->crossposted = time ();
		return $this->crosspost->save ();
	}
}