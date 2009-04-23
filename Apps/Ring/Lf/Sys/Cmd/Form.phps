<?php
class R_Lf_Sys_Cmd_Form extends R_Lf_Sys_Command {
	public $creative_id;

	public function process()
	{
		if ($this->creative_id) {
			$creative = $this->instance->getCreative( $this->creative_id );
			if (!$creative)
				return $this->redirect( "/" );
		}

		$form = new O_Dao_Renderer_FormProcessor( );
		$form->setClass( constant(get_class($this->instance)."::CREATIVE_CLASS") );

		$form->setRelationQuery( "tags", $this->getSite()->tags, "title", true );

		if ($this->creative_id) {
			$form->setActiveRecord( $creative );
		} else {
			$form->setCreateMode( $this->instance );
		}
		if ($form->handle()) {
			$creative = $form->getActiveRecord();
			$creative->owner = R_Mdl_Session::getUser();
			$creative->save();
			return $this->redirect( $creative->url() );
		}

		$tpl = $this->getTemplate();
		$tpl->form = $form;
		$tpl->isCreateMode = !$this->creative_id;
		return $tpl;
	}

	public function isAuthenticated()
	{
		return $this->instance && $this->can( "read " . $this->instance->system[ "access" ], $this->getSite() ) && $this->can(
				"write", $this->getSite() );
	}

}