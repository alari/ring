<?php
/**
 * @table - -show:callback R_Mdl_Comment::showComment
 * @field time INT -show date
 * @field content TEXT -edit area -show -required Нужно обязательно что-то ввести
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
		?>
<div style="margin-left:<?=$comment->level?>em">
<?=$params->value()?>
</div>

<?
		self::addForm( $comment[ "root" ], $comment->root->getSystemId(), $comment->id );
	}

	static public function addForm( $rootId, $systemId, $parent = 0 )
	{
		?>
<div>
<div align="right"><a href="javascript:void(0)"
	onclick="R.Comment.showForm($(this).getParent(),'<?=O_UrlBuilder::get( "comment" )?>',<?=$rootId?>,<?=$parent?>,<?=$systemId?>)"><?=($parent ? "Ответить" : "Оставить отзыв")?></a></div>
</div>
<?
	}


}