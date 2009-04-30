<?php
class R_Ctr_Cmd_Own_EditProfile extends R_Command {

	public function process()
	{
		/* @var $user R_Mdl_User */
		$user = R_Mdl_Session::getUser();
		$form = $user->form();

		if ($this->getParam( "action" ) == "upload-ava") {
			if ($user->avatar_ext != "-") {
				if (is_file( $user->staticFilename( "ava." . $user->avatar_ext ) ))
					unlink( $user->staticFilename( "ava." . $user->avatar_ext ) );
				if (is_file( $user->staticFilename( "ava-full." . $user->avatar_ext ) ))
					unlink( $user->staticFilename( "ava-full." . $user->avatar_ext ) );
				$user->avatar_ext = "-";
				$user->save();
			}

			if (!isset( $_FILES[ "ava" ] ) || !$_FILES[ "ava" ][ "size" ]) {
				return $this->redirect();
			}

			$file = $_FILES[ "ava" ];
			$ava_file = $user->staticFilename( "ava" );
			$user->createUserdir();

			try {
				$resizer = new O_Image_Resizer( $file[ "tmp_name" ] );

				$resizer->resize( 200, 500, $ava_file . "-full", true );
				preg_match( "#(gif|jpeg|png)$#", $resizer->resize( 80, 200, $ava_file, true ), $p );
				$user->avatar_ext = $p[ 1 ];
				$user->save();
			}
			catch (O_Ex_WrongArgument $e) {
				$this->setNotice( "Можно закачивать картинки только в форматах jpg, gif, png." );
				return $this->redirect();
			}
			catch (O_Ex_NotFound $e) {
				$this->setNotice( "Ошибка при закачке картинки." );
				return $this->redirect();
			}
			$user->save();
			print_r($_POST);

			return $this->redirect();
		} else {
			$form->handle();
		}

		$tpl = $this->getTemplate();
		$tpl->form = $form;
		return $tpl;

	}

	public function isAuthenticated()
	{
		return R_Mdl_Session::isLogged();
	}

}