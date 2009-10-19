<?php
class R_Mr_Cmd_EditPage extends R_Command {

	public function process()
	{
		$form = new O_Form_Handler( "R_Mdl_Info_Page" );

		$pageTitle = strtr( urldecode( substr( O_Registry::get( "app/env/process_url" ), 5 ) ),
				"_", " " );

		$page = R_Mdl_Info_Page::getByUrlName( $pageTitle );
		if ($page instanceof R_Mdl_Info_Page) {
			$form->setRecord( $page );
		} else {
			$form->setCreateMode( $pageTitle );
		}
		$form->setRelationQuery( "topics", O_Dao_Query::get( "R_Mdl_Info_Topic" ), "title" );

		if ($form->handle()) {
			if ($this->getParam( "topic_new" )) {
				try {
					$topic = new R_Mdl_Info_Topic( $this->getParam( "topic_new" ) );
					$form->getRecord()->topics[] = $topic;
				}
				catch (Exception $e) {
				}
			}
			return $this->redirect( $form->getActiveRecord()->url() );
		}

		$tpl = $this->getTemplate();
		$tpl->form = $form;
		return $tpl;
	}

	public function isAuthenticated()
	{
		return R_Mdl_Session::isLogged() && R_Mdl_Session::getUser()->isOurUser();
	}

}