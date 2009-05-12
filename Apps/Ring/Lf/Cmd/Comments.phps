<?php
class R_Lf_Cmd_Comments extends R_Lf_Command {

	public function process()
	{
		$tpl = $this->getTemplate();

		$query = $this->getSite()->{"anonces.nodes"}->orderBy("time DESC");
		$tpl->title = $this->instance->title;

		R_Mdl_Session::setQueryAccesses($query, $this->getSite());
		$tpl->paginator = $query->getPaginator(
				array ($this, "url") );

		$tpl->site = $this->getSite();
		return $tpl;
	}

	public function url($page) {
		return $this->getSite()->url("comments".($page>1?"-".$page:""));
	}
}