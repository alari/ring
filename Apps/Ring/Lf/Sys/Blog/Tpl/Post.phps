<?php
class R_Lf_Sys_Blog_Tpl_Post extends R_Lf_Sys_Blog_Template {
	public $post;
	public $form;

	public function displayContents()
	{
		$this->post->show( $this->layout() );
		$this->post->nodes->show( $this->layout() );
		R_Mdl_Comment::addForm( $this->post->id, $this->post->getSystemId() );
		$this->layout()->setTitle( $this->post->title );
	}

}