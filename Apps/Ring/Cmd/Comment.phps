<?php
class R_Cmd_Comment extends R_Command {
	private $system;
	/**
	 *
	 * @var R_Mdl_Site_Anonce
	 */
	private $root;

	public function process()
	{
		Header( "Content-type: text/html; charset=utf-8" );

		if ($this->getParam( "action" ) == "comment-new" || $this->getParam( "action" ) == "comment-for") {
			if (!R_Mdl_Session::isLogged()) {
				return "Вы не можете оставлять отзывы. Вероятно, Вам просто нужно авторизоваться. (<a href='http://" .
					 O_Registry::get( "app/hosts/project" ) . "/OpenId' target='_blank'>Как?</a>)";
			}
			if(!$this->can("comment", $this->root)){
				return "Отзывы на страничке отключены.";
			}
			$this->handleForm();
			return;
		}

		if ($this->getParam( "action" ) == "delete") {
			$comment = $this->root->nodes->test( "id", $this->getParam( "comm" ) )->getOne();
			if (!$comment)
				return json_encode(
						array ("status" => "FAILED", "message" => "Комментарий не найден.") );
			if (!$this->can( "delete", $comment ))
				return json_encode(
						array ("status" => "FAILED", "message" => "Недостаточно прав.") );
			$ids = Array ();
			/* @var $comment R_Mdl_Site_Comment */
			foreach ($comment->getChilds()->field( "id" )->select() as $id) {
				$ids[] = $id[ "id" ];
			}
			$ids[] = $comment->id;
			$comment->delete( true );
			return json_encode( array ("status" => "SUCCEED", "comments" => $ids) );
		}

		return "Неизвестное действие.";

	}

	private function handleForm()
	{
		$form = new O_Form_Handler( "R_Mdl_Site_Comment" );
		$form->addHidden( "root", $this->root->id );
		$form->addHidden( "sys", $this->system->id );
		$form->addHidden( "ajax-driven", "yes" );
		$form->addHidden( "action", $this->getParam( "action" ) );
		$form->setAjax();

		$form->addSubmitButton( "Сохранить" );

		if ($this->getParam( "action" ) == "comment-for") {
			$form->setCreateMode( $this->root );
			$form->getFieldset()->setLegend( "Отозваться" );
			$form->addHidden( "parent-node", $this->getParam( "parent-node" ) );
			$parent = $this->root->nodes->test( "id", $this->getParam( "parent-node" ) )->getOne();
			if (!$parent) {
				return $this->returnForm( $form, 1 );
			}
		} elseif ($this->getParam( "action" ) == "comment-new") {
			$form->setCreateMode( $this->root );
			$form->getFieldset()->setLegend( "Оставить отзыв" );
		}

		if ($this->getParam( "ajax-driven" ) == "yes" && $form->handle()) {
			$comment = $form->getRecord();
			if (isset( $parent )) {
				$parent->injectBottom( $comment );
			}
			$comment->save();
			// XXX
			$comment->notifySubscribers();

			$form->getRecord()->reload();
			return $form->responseAjax();
		}
		return $this->returnForm( $form );
	}

	private function returnForm( $form, $notFound = 0 )
	{
		if ($this->getParam( "ajax-driven" ) == "yes") {
			if ($notFound)
				echo json_encode(
						array ("status" => "FAILED",
									"errors" => array (
																"_" => "Error: parent node not found.")) );
			$form->responseAjax();
			return null;
		}
		return $form->render();
	}

	public function isAuthenticated()
	{
		$this->system = O_Dao_ActiveRecord::getById( $this->getParam( "sys" ),
				"R_Mdl_Sys_Instance" );
		if (!$this->system)
			throw new O_Ex_PageNotFound( "System not found.", 404 );
		$this->root = $this->system->site->anonces->test( "id", $this->getParam( "root" ) )->getOne();
		if (!$this->root)
			throw new O_Ex_PageNotFound( "Parent not found.", 404 );
		if ($this->root->system != $this->system)
			return false;
		if (!R_Mdl_Session::can( "read " . $this->root[ "access" ], $this->system->site ) )
			return false;
		return true;
	}

}