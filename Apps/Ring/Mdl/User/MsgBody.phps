<?php
/**
 * @table user_msgs_body
 *
 * @field msg -owns one R_Mdl_User_Msg -inverse msg_body
 *
 * @field content MEDIUMTEXT
 */
class R_Mdl_User_MsgBody extends O_Dao_ActiveRecord {

}