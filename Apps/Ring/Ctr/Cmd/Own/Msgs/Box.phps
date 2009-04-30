<?php
class R_Ctr_Cmd_Own_Msgs_Box extends R_Command {
	private $box;

	public function process() {
		$box = O_Registry::get("app/current/box");
		if($box != "Inbox" && $box != "Sent" && $box != "Trash") $box = "Inbox";

		$q = R_Mdl_Session::getUser()->msgs_own->test("box", $box);
		$tpl = $this->getTemplate();
		$tpl->paginator = $q->getPaginator(Array($this, "url"));
		$tpl->box = $box;
		$this->box = $box;

		return $tpl;
	}

	public function url($page) {
		return O_UrlBuilder::get("Own/Msgs/".($this->box != "Inbox" || $page>1 ? $this->box : "").($page>1?"-".$page:""));
	}


	public function isAuthenticated() {
		return R_Mdl_Session::isLogged();
	}


}