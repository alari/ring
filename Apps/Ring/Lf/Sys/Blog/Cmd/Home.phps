<?php
class R_Lf_Sys_Blog_Cmd_Home extends R_Lf_Sys_Blog_Command {
	public $blog;
	public $tag;

	public function process()
	{
		$tpl = $this->getTemplate();
		try {
			if($this->tag) {
				$query = $this->tag->{"anonces.blog_post"}->test("system", $this->blog->system->id);
			} else {
				$query = $this->blog->system->{"anonces.blog_post"};
			}
			$tpl->paginator = new O_Dao_Paginator( $query,
					array ($this, "url"), $this->blog->perpage );
		}
		catch (O_Ex_PageNotFound $e) {

		}
		$tpl->site = $this->getSite();
		$tpl->title = $this->blog->title;
		$tpl->tag = $this->tag;
		$tpl->tags = $this->blog->system->site->tags->test("weight", 0, ">");
		return $tpl;
	}

	public function url($page) {
		return $this->tag ? $this->tag->url($this->blog->system->urlbase.($page>1?"/".$page:"")): $this->blog->url($page);
	}


	public function isAuthenticated()
	{
		return $this->blog && $this->can( "read " . $this->blog->system[ "access" ], $this->getSite() );
	}

}