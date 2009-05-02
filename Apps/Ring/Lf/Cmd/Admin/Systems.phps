<?php
class R_Lf_Cmd_Admin_Systems extends R_Lf_Command {

	public function process()
	{
		$systems = $this->getSite()->systems;

		if ($this->isMethodPost()) {
			if ($this->getParam( "action" ) == "create-system") {
				$type = $this->getParam( "type" );
				$title = $this->getParam( "title" );
				$urlbase = $this->getParam( "urlbase" );
				if (!preg_match( "#^[-_[:alnum:]]+$#", $urlbase ) || $systems->test( "urlbase", $urlbase )->getOne()) {
					$this->setNotice(
							"Префикс адреса системы должен состоять только из латинских символов, подчерка и дефиса и быть уникальным на сайте." );
					return $this->redirect();
				}
				$classes = R_Mdl_Site_System::getClasses();
				if ($title && isset( $classes[ $type ] )) {
					$class = $classes[ $type ];
					$s = new $class( );
					$sys = new R_Mdl_Site_System( $title, $urlbase, $this->getSite() );
					$sys->access = $this->getParam( "access", "public" );
					$sys->instance = $s;
				} else {
					$this->setNotice( "Введите заголовок системы, иначе нечего отображать в главном меню!" );
				}
				return $this->redirect();
			} elseif ($this->getParam( "action" ) == "system-fragment") {
				$tpl = $this->getTemplate();
				$tpl->types = R_Mdl_Site_System::getTitles();
				$tpl->systemEditFragment(
						isset( $systems[ $this->getParam( "sys" ) ] ) ? $systems[ $this->getParam( "sys" ) ] : null );
				return;
			} elseif ($this->getParam( "action" ) == "edit-system") {
				$response = Array ("status" => "");
				if (!isset( $systems[ $this->getParam( "sys" ) ] )) {
					$response[ "status" ] = "FAILED";
					$response[ "error" ] = "Система не найдена. Проверьте авторизацию.";
					return json_encode( $response );
				}
				$sys = $systems[ $this->getParam( "sys" ) ];
				$sys->access = $this->getParam( "access" );
				$sys->title = $this->getParam( "title" );
				$sys->save();
				$response[ "status" ] = "SUCCEED";
				$response[ "show" ] = "Изменения успешно сохранены. Обновите страницу, если хотите немедленно их пронаблюдать.";
				return json_encode( $response );
			}
		}

		$tpl = $this->getTemplate();
		$tpl->systems = $systems;
		$tpl->types = R_Mdl_Site_System::getTitles();
		return $tpl;
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}