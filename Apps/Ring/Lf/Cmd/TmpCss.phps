<?php
class R_Lf_Cmd_TmpCss extends R_Lf_Command {

	public function process()
	{
		if(!isset($_SESSION["c"]) || !is_array($_SESSION["c"])) return;
		$c = $_SESSION["c"];
		header("Content-type: text/css");
?>

#head{color:<?=$c[1]?>}
#head a:hover{color:<?=$c[1]?>}
#main-menu{border-bottom-color:<?=$c[1]?>}
#foot{background-color:<?=$c[1]?>}
.system h2 a:hover{color:<?=$c[1]?>}
.anonce .cnt{background-color:<?=$c[1]?>}
h1{color:<?=$c[1]?>}

.system h2 a{color:<?=$c[2]?>}
.system h2{border-bottom-color:<?=$c[2]?>}
.comm-ava img{border-color:<?=$c[2]?>}
.comm{border-left:1px solid <?=$c[2]?>;border-top:1px solid <?=$c[2]?>}

#main-menu,#rcol,.anonce,.anonce strong{background-color:<?=$c[3]?>}

body{color:<?=$c[4]?>}

#head{background-color:<?=$c[5]?>}
#main-menu a{color:<?=$c[5]?>}
#rcol{border-color:<?=$c[5]?>}
.system h2{background-color:<?=$c[5]?>}

#foot{border-top-color:<?=$c[6]?>}
.anonce strong a,#foot,.anonce,hr{color:<?=$c[6]?>}

h2,h3,h4,body a,#rcol li a,#head a{color:<?=$c[7]?>}
#main-menu{border-bottom-color:<?=$c[7]?>}

#openid span{color:<?=$c[8]?>}

#main-menu a,#rcol a,#foot a{color:<?=$c[9]?>}

#wrap{background-color:<?=$c[10]?>}

<?
	}

}