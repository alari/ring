<?php
class R_Lf_Sys_Blog_Cmd_Form extends R_Lf_Sys_Blog_Command {
	public $post_id;
	public $blog;

	public function process()
	{
		if ($this->post_id) {
			$post = $this->blog->getCreative($this->post_id );
			if (!$post)
				return $this->redirect( "/" );
		}

		$form = new O_Dao_Renderer_FormProcessor( );
		$form->setClass( "R_Mdl_Blog_Post" );

		$form->setRelationQuery( "tags", $this->getSite()->tags, "title", true );

		if ($this->post_id) {
			$form->setActiveRecord( $post );
		} else {
			$form->setCreateMode(array($this->blog));
		}
		if ($form->handle()) {
			$post = $form->getActiveRecord();
			$post->owner = R_Mdl_Session::getUser();
			$post->save();
			return $this->redirect( $post->url() );
		}

		$tpl = $this->getTemplate();
		$tpl->form = $form;
		$tpl->isCreateMode = !$this->post_id;
		return $tpl;
	}

	public function isAuthenticated()
	{
		return $this->blog && $this->can( "read " . $this->blog->system["access"], $this->getSite() ) && $this->can(
				"write", $this->getSite() );
	}

}