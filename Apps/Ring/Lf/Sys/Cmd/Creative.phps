<?php
class R_Lf_Sys_Cmd_Creative extends R_Lf_Sys_Command {

	public function process()
	{
		if($this->getParam("action") == "delete") {
			if(!$this->can("delete", $this->getSite())) throw new O_Ex_AccessDenied("Вы не можете удалять записи на этом сайте.");
			$this->creative->delete();
			return $this->redirect($this->instance->system->url());
		} elseif($this->getParam("action") == "fav") {
			if(!R_Mdl_Session::isLogged()) return;
			$user = R_Mdl_Session::getUser();
			if($user->favorites->has($this->creative->anonce)) {
				$user->favorites->remove($this->creative->anonce);
				return "<b>Убрали</b>";
			} else {
				$user->favorites[] = $this->creative->anonce;
				return "<b>Добавили</b>";
			}
		}

		$tpl = $this->getTemplate();
		$tpl->creative = $this->creative;
		$tpl->tags = $this->creative->tags;
		return $tpl;
	}

	public function isAuthenticated()
	{
		if (!$this->instance)
			throw new O_Ex_PageNotFound( "instance not found", 404 );
		$this->creative = $this->instance->getCreative( $this->creative_id );
		if (!$this->creative)
			throw new O_Ex_PageNotFound( "creative not found", 404 );
		return $this->can( "read " . $this->instance->system[ "access" ], $this->getSite() ) && $this->can(
				"read " . $this->creative->anonce[ "access" ], $this->getSite() );
	}

}