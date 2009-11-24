<?php
abstract class R_Mdl_Site_Crosspost_Logic {
	protected $crosspost;
	
	public function setCrosspost(R_Mdl_Site_Crosspost $crosspost) {
		$this->crosspost = $crosspost;
	}
	
	protected function error($msg) {
		if ($this->crosspost) {
			$this->crosspost->error_msg = $msg;
			$this->crosspost->error_time = time ();
			$this->crosspost->save ();
		}
		return false;
	}
	
	abstract public function post();
	
	abstract public function update();
	
	abstract public function delete();
}