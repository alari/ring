<?php
use O;
O( "*url_dispatcher", function(){
	// To prevent IDE from warnings
	$p = Array();
	// Shortcuts for the most usable callbacks
	$M = function($pattern, $registry, &$matches=null) {
		return preg_match("#^$pattern$#i", O($registry), $matches);
	};
	$PG = function($pattern, &$matches=null) {
		$r = preg_match("#^$pattern(\.page-([0-9]+))?$#i", O("~process_url"), $matches);
		if(!$r) return false;
		if(is_numeric($matches[count($matches)-1])) O("*paginator/page", $matches[count($matches)-1]);
		return true;
	};
	$URL = function($pattern, &$matches=null) {
		return preg_match("#^$pattern$#i", O("~process_url"), $matches);
	};

	// Determine current plugin
	O("*plugin", "Lf");
	if(O("~http_host") == O("_hosts/project")) {
		O("*plugin", "Mr");
	} elseif(O("~http_host") == O("_hosts/center")) {
		O("*plugin", "Ctr");
	}

	// OpenId and other plugin-independent utilites
	if($URL("openid/(redirect|login|logout)", $p)) {
		O("*plugin", "");
		O("*command", "OpenId_".ucfirst($p[1]));
	} elseif($URL("openid/provider(/(.+))?", $p)) {
		O("*current/action", $p[2]);
		O("*plugin", "");
		O("*command", "OpenId_Provider");
	} elseif($URL("comment")) {
		O("*plugin", "");
		O("*command", "Comment");
	} elseif(!O("~process_url")) {
		O("*command", "Home");
	}

	if(O("*plugin") == "Lf") {
		if(strpos(O("~process_url"), ".")) {
			if($PG('friends\.feed')) {
				O("*command", "Friends_Feed");
			}
		} elseif($PG('commants\.feed')) {
			O("*command", "Comments_Feed");
		} elseif($URL('tag/(([0-9]+)/)?(.+)', $p)) {
			O("*current/tag", $p[3]);
			O("*paginator/page", $p[2]);
			O("*command", "Tag");
		}
		if($M('(www\.|openid\.)?(.+)', "~http_host", $p)) {
			O("*current/site", R_Mdl_Site::getByHost($p[2]));
		}
		O("*layout_class", "R_Lf_Layout");
	} elseif(O("*plugin") == "Ctr") {
		if($URL("own/msgs/read-([0-9]+)", $p)) {
			O("*current/msg", $p[1]);
			O("*command", "Own_Msgs_Read");
		} elseif($URL('own/msgs/write(-([0-9]+))', $p)) {
			O("*current/adresate", $p[2]);
			O("*command", "Own_Msgs_Write");
		} elseif($URL('Own/Msgs/(([a-zA-Z]+)(-([0-9]+))?)?', $p)) {
			O("*current/box", $p[2]);
			O("*paginator/page", $p[4]);
			O("*command", "Own_Msgs_Box");
		} elseif($URL('Own/Friends-([0-9]+)', $p)) {
			O("*paginator/page", $p[1]);
			O("*command", "Own_Friends");
		}
		O("*layout_Class", "R_Ctr_Layout");
		// TODO: understand this
		O("*dict/default/base/filebase", "Apps/Ring/Ctr/dict.");
	} elseif(O("*plugin") == "Mr") {
		if($URL('topic:(.+)', $p)) {
			O("*command", "Topic");
			O("*current/topic", R_Mdl_Info_Topic::getByUrlName($p[1]));
		} elseif($URL('edit:(.+)')) {
			O("*command", "EditPage");
		} else {
			O("*command", "Page");
			O("*current/page", R_Mdl_Info_Page::getByUrlName(O("~process_url")));
		}
		O("*layout_class", "R_Mr_Layout");
	}
} );