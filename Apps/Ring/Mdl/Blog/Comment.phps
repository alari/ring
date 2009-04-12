<?php
/**
 * @table blog_comment
 * @field owner -has one R_Mdl_User -inverse blog_comments -preload
 */
class R_Mdl_Blog_Comment extends R_Mdl_Comment {
	const ROOT_CLASS = "R_Mdl_Blog_Post";
}