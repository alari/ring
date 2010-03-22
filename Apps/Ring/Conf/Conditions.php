<?php
O("*conditions", function(){
	if(O("~http_host") == "tests.utils.mir.io") {
		O("*mode", "testing");
		O("_db/default", Array(
			"engine"=>"mysql",
			"host"=>"localhost:3306",
			"dbname"=>"ring_tests",
			"user"=>"ring_tests",
			"password"=>"fEQD10xv"
		));
	} elseif(!strpos(O("~http_host"), ".")) {
		O("*mode", "development");
		O("_db/default", Array(
			"engine"=>"mysql",
			"host"=>"localhost",
			"dbname"=>"ring"
		));
		O("_hosts", Array(
			"center"=>"centralis",
			"project"=>"mirari"
		));
		O("_html/static_root", "/static/");
	} else {
		O("*mode", "production");
		O("_db/default", Array(
			"engine"=>"mysql",
			"host"=>"localhost:3306",
			"dbname"=>"ring",
			"user"=>"ring",
			"password"=>"XKSLzapa"
		));
		O("_hosts", Array(
			"center"=>"centralis.name",
			"project"=>"mirari.name"
		));
		O("_html/static_root", "http://centralis.name/static/");
	}
	O("_prefix", "R");
	O("_ext", "phps");
});
?>
prefix: R
ext: phps