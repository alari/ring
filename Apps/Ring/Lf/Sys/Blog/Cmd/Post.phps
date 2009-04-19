<?php
class R_Lf_Sys_Blog_Cmd_Post extends R_Lf_Sys_Blog_Command {
	public $post_id;
	public $blog;
	protected $post;

	public function process()
	{
		$tpl = $this->getTemplate();
		$tpl->post = $this->post;
		$tpl->tags = $this->post->tags;
		return $tpl;
	}

	public function isAuthenticated()
	{
		if (!$this->blog)
			throw new O_Ex_PageNotFound( "Blog not found", 404 );
		$this->post = $this->blog->getCreative( $this->post_id );
		if (!$this->post)
			throw new O_Ex_PageNotFound( "Post not found", 404 );
		return $this->can( "read " . $this->blog->system->access, $this->getSite() ) && $this->can(
				"read " . $this->post->access, $this->getSite() );
	}

}