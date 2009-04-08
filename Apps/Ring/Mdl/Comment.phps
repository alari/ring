<?php
/**
 * @field time INT -show date
 * @field content TEXT -show R_Mdl_Comment::showComment -edit area
 */
abstract class R_Mdl_Comment extends O_Dao_NestedSet_Node {
	private static $form;

	public function __construct( O_Dao_ActiveRecord $root )
	{
		if (!R_Mdl_Session::isLogged())
			throw new O_Ex_Logic( "Cannot create comment for not logged user." );
		$this->time = time();
		$this->owner = R_Mdl_Session::getUser();
		parent::__construct( $root );
	}

	static public function showComment( O_Dao_Renderer_Show_Params $params )
	{
		$comment = $params->record();
		if (!$comment instanceof self) {
			echo "Error<br/>";
			return;
		}
		
		if (!self::$form) {
			self::$form = new O_Dao_Renderer_FormProcessor( );
			self::$form->setClass( get_class( $comment ) );
			self::$form->addHiddenField( "action", "comment" );
			self::$form->setAjaxMode();
		}
		
		self::$form->addHiddenField( "parent-node", $comment->id );
		?>
<div style="margin-left:<?=$comment->level?>em">
<?=$comment->content?>
</div>
<?
		self::$form->show( $params->layout() );
	}

	static public function handleForm( R_Command $cmd, O_Dao_NestedSet_Root $root )
	{
		$form = new O_Dao_Renderer_FormProcessor( );
		$form->setClass( constant( get_class( $root ) . "::NODES_CLASS" ) );
		$form->setCreateMode( array ($root) );
		$form->setAjaxMode();
		if ($cmd->getParam( "action" ) == "comment") {
			$parent = $root->nodes->test( "id", $cmd->getParam( "parent-node" ) )->getOne();
			if (!$parent)
				return $form;
		}
		if ($form->handle()) {
			$comment = $form->getActiveRecord();
			if (isset( $parent )) {
				$parent->injectBottom( $comment );
			}
			$comment->save();
			$comment->reload();
			ob_start();
			$comment->show();
			
			$response = array ("status" => "SUCCEED", "show" => ob_get_clean());
			echo json_encode( $response );
			return null;
		}
		return $form;
	}

}