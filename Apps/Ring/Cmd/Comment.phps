<?php
class R_Cmd_Comment extends R_Command {
	private $system;
	private $root;

	public function process()
	{
		if (!$this->can( "comment " . $this->system->access, $this->system->site )) {
			return "Вы не можете оставлять отзывы. Вероятно, Вам просто нужно авторизоваться.";
		}

		if ($this->getParam( "action" ) == "comment-new" || $this->getParam( "action" ) == "comment-for") {
			$this->handleForm();
		}

	}

	private function handleForm()
	{
		$form = new O_Dao_Renderer_FormProcessor( );
		$form->setClass( constant( get_class( $this->root ) . "::NODES_CLASS" ) );
		$form->addHiddenField( "root", $this->root->id );
		$form->addHiddenField( "sys", $this->system->id );
		$form->addHiddenField( "ajax-driven", "yes" );
		$form->addHiddenField( "action", $this->getParam( "action" ) );
		$form->setAjaxMode();

		$form->setSubmitButtonValue("Сохранить");

		if ($this->getParam( "action" ) == "comment-for") {
			$form->setCreateMode( array ($this->root) );
			$form->setFormTitle( "Отозваться" );
			$form->addHiddenField( "parent-node", $this->getParam( "parent-node" ) );
			$parent = $this->root->nodes->test( "id", $this->getParam( "parent-node" ) )->getOne();
			if (!$parent) {
				return $this->returnForm( $form, 1 );
			}
		} elseif ($this->getParam( "action" ) == "comment-new") {
			$form->setCreateMode( array ($this->root) );
			$form->setFormTitle( "Оставить отзыв" );
		}

		if ($this->getParam( "ajax-driven" ) == "yes" && $form->handle()) {
			$comment = $form->getActiveRecord();
			if (isset( $parent )) {
				$parent->injectBottom( $comment );
			}
			$comment->save();
			$comment->reload();
			return $form->responseAjax();
		}
		return $this->returnForm( $form );
	}

	private function returnForm( $form, $notFound = 0 )
	{
		if ($this->getParam( "ajax-driven" ) == "yes") {
			if ($notFound)
				echo json_encode(
						array ("status" => "FAILED", "errors" => array ("_" => "Error: parent node not found.")) );
			$form->responseAjax();
			return null;
		}
		return $form->show();
	}

	public function isAuthenticated()
	{
		$this->system = O_Dao_ActiveRecord::getById( $this->getParam( "sys" ), "R_Mdl_Site_System" );
		if (!$this->system)
			throw new O_Ex_PageNotFound( "System not found.", 404 );
		$this->root = $this->system->getSystem()->getItem( $this->getParam( "root" ) );
		if (!$this->root)
			throw O_Ex_PageNotFound( "Parent not found.", 404 );
		return true;
	}

}