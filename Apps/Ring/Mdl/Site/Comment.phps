<?php
/**
 * @table site_comments -show-list:callback R_Fr_Site_Comment::showCallback list
 *
 * @field owner -has one R_Mdl_User -inverse comments
 *
 * @field time INT -show date
 * @field content TEXT -edit area -show -required Нужно обязательно что-то ввести -title Комментарий -check R_Mdl_Site_Comment::checkContent
 */
class R_Mdl_Site_Comment extends O_Dao_NestedSet_Node {
	const ROOT_CLASS = "R_Mdl_Site_Anonce";

	public function __construct( O_Dao_ActiveRecord $root )
	{
		if (!R_Mdl_Session::isLogged())
			throw new O_Ex_Logic( "Cannot create comment for not logged user." );
		$this->time = time();
		$this->owner = R_Mdl_Session::getUser();
		parent::__construct( $root );
	}

	static public function checkContent(O_Dao_Renderer_Check_Params $params){
		$params->setNewValue(nl2br($params->newValue()));
	}
}