<?php
/**
 * @table blog_post
 *
 * @field:config anonce -inverse blog_post
 *
 * @field content MEDIUMTEXT -show -edit wysiwyg -required Текст записи необходим -check htmlPurify -title
 *
 * @field:replace content,tags
 *
 */
class R_Mdl_Sound_Track extends R_Mdl_Site_Creative {

}