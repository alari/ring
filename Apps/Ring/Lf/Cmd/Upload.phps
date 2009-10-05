<?php
class R_Lf_Cmd_Upload extends R_Lf_Command {

	public function process()
	{
		file_put_contents( "C:/http/ring/tmp.txt", 
				print_r( $_POST, 1 ) . "\n\n" . print_r( $_GET, 1 ) . "\n\n" . print_r( $_FILES, 
						1 ) );
	}

}