<?php
class R_Lf_Cmd_Admin_SiteView extends R_Lf_Command {

	public function process()
	{
		if (O_Registry::get( "app/env/request_method" ) == "POST") {
			if ($this->getParam( "action" ) == "css") {
				$css = $this->getParam( "css" );
				if ($css)
					file_put_contents( $this->getSite()->static_folder . "style.css", $css );
				return $this->redirect();
			} elseif ($this->getParam( "action" ) == "file") {
				if (!isset( $_FILES[ "f" ] ) || !$_FILES[ "f" ][ "size" ])
					return $this->redirect();
				$file = $_FILES[ "f" ];
				if ($file[ "size" ] > 120 * 1024) {
					$this->setNotice("Файл слишком большой.");
					return $this->redirect();
				}
				if (!preg_match( "#^[-_\\.[:alnum:]]+\\.(jpg|gif|png)$#", $file[ "name" ] )) {
					$this->setNotice("Картинка должна быть в jpg, gif или png и иметь название, написанное латиницей.");
					return $this->redirect();
				}
				if (file_exists( $this->getSite()->static_folder . $file[ "name" ] ))
					unlink( $this->getSite()->static_folder . $file[ "name" ] );
				move_uploaded_file( $file[ "tmp_name" ], $this->getSite()->static_folder . $file[ "name" ] );
				return $this->redirect();
			} elseif($this->getParam("action") == "favicon") {
				
				if (file_exists( $this->getSite()->staticPath( "favicon.ico") ))
					unlink( $this->getSite()->staticPath( "favicon.ico") );
					
				if (!isset( $_FILES[ "f" ] ) || !$_FILES[ "f" ][ "size" ])
					return $this->redirect();
				$file = $_FILES["f"];
				if(substr($file["name"], -4) != ".ico") {
					$this->setNotice("Иконка должна быть в формате .ico");
					return $this->redirect();
				}
				
				move_uploaded_file( $file[ "tmp_name" ], $this->getSite()->staticPath( "favicon.ico" ) );
				return $this->redirect();
			}
		} else {
			if($this->getParam("action") == "revert") {
				file_put_contents($this->getSite()->static_folder."style.css", file_get_contents($this->getSite()->static_folder."../style.css"));
				return $this->redirect();
			}

			$tpl = $this->getTemplate();
			$tpl->css_source = file_get_contents( $this->getSite()->static_folder . "style.css" );
			$d = opendir( $this->getSite()->static_folder );
			while ($f = readdir( $d ))
				if (is_file( $this->getSite()->static_folder . $f )) {
					if ($f == "style.css")
						continue;
					if ($this->getParam( "delete" ) == $f) {
						unlink( $this->getSite()->static_folder . $f );
						continue;
					}
					$tpl->files[] = $f;
				}
			return $tpl;
		}
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}