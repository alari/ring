<?php
class R_Lf_Sys_Blog_Cmd_Home extends R_Lf_Sys_Blog_Command {
	public $blog;
	public $tag;

	public function process()
	{
		$tpl = $this->getTemplate();
		try {
			$tpl->paginator = new O_Dao_Paginator( $this->blog->system->{"anonces.blog_post"}, array ($this->blog, "url"),
					$this->blog->perpage );
		}
		catch (O_Ex_PageNotFound $e) {

		}
		$tpl->site = $this->getSite();
		$tpl->title = $this->blog->title;
		return $tpl;
	}

	public function isAuthenticated()
	{
		return $this->blog && $this->can( "read " . $this->blog->system["access"], $this->getSite() );
	}

}