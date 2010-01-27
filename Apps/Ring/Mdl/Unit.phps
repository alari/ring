<?php
/**
 * @table units
 *site 23
 * @field url_part VARCHAR(12) NOT NULL
 * @field url_cache VARCHAR(255) NOT NULL
 *
 * @field title VARCHAR(255)
 *
 * @field type TINYINT NOT NULL DEFAULT 0
 * @field content INT
 *
 * @index root,url_cache(24)
 */
class R_Mdl_Unit extends O_Dao_NestedSet_Both {
	const ROOT_CLASS = "R_Mdl_Site";
	const NODES_CLASS = "R_Mdl_Comment";

	const ALLOW_READ = 1;
	const ALLOW_WRITE = 2;
	const ALLOW_DELETE = 4;
	const ALLOW_COMMENT = 8;
	const ALLOW_ADMIN = 16;

	static private $types = Array(0=>"Folder", 1=>"Text");
}