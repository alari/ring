<?php
abstract class R_Command extends O_Command {

	public function can( $action, O_Dao_ActiveRecord $resourse = null )
	{
		return R_Mdl_Session::getUser()->can( $action, $resourse );
	}

	public function isMethodPost()
	{
		return O_Registry::get( "app/env/request_method" ) == "POST";
	}

	public function setNotice( $notice )
	{
		$_SESSION[ "notice" ] = $notice;
	}

	public function catchEx( Exception $e )
	{
		if ($e instanceof O_Ex_AccessDenied) {
			$this->setNotice( "Вы не авторизованы или у вас нет прав на просмотр страницы." );
			$this->redirect( "/" );
			return;
		}
		throw $e;
	}

}