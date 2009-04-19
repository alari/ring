<?php
class R_Lf_Cmd_Admin_Tags extends R_Lf_Command {

	public function process()
	{

		if ($this->getParam( "action" ) == "tag-fragment") {
			$tag = $this->getSite()->tags->test( "id", $this->getParam( "tag" ) )->getOne();
			if (!$tag)
				return "Метка не найдена.";
			$form = $tag->form();
			$form->setAjaxMode();
			$form->addHiddenField( "tag-submitted", "yes" );
			$form->addHiddenField( "action", "tag-fragment" );
			$form->addHiddenField( "tag", $tag->id );
			if ($this->getParam( "tag-submitted" ) == "yes") {
				$form->responseAjax( null, "Изменения успешно сохранены" );
				return null;
			} else {
				$tpl = $this->getTemplate();
				$tpl->form = $form;
				return $tpl->tagEditFragment();
			}
		} elseif ($this->getParam( "action" ) == "tag-delete") {
			$tag = $this->getSite()->tags->test( "id", $this->getParam( "tag" ) )->getOne();
			if (!$tag)
				return "Метка не найдена.";
			$tag->delete();
			return "Метка удалена.";
		}

		$form = new O_Dao_Renderer_FormProcessor( );
		$form->setClass( "R_Mdl_Site_Tag" );
		$form->setCreateMode( array ($this->getSite()) );
		if ($form->handle())
			return $this->redirect();

		$tpl = $this->getTemplate();
		$tpl->tags = $this->getSite()->tags;
		$tpl->form = $form;
		return $tpl;
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}