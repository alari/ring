<?php
/**
 * @table user_msgs -loop:envelop container ul -show-loop:envelop container li
 *
 * @field owner -has one R_Mdl_User -inverse msgs_own
 * @field target -has one R_Mdl_User -inverse msgs_target -show -edit simple -title OpenId адресата -required Напишите, кому!
 *
 * @field box ENUM('inbox','sent','trash') DEFAULT 'inbox' -enum inbox:Входящие; sent:Отправленные; trash:Корзина -show-def
 *
 * @field title VARCHAR(255) -edit -show-loop linkInContainer -show-def container h1 -title Тема сообщения
 *
 * @field msg_body -owns one R_Mdl_User_MsgBody -inverse msg
 * @field content -relative msg_body->content -show-def -edit wysiwyg -check htmlPurify -title -required Что-то в письме должно быть.
 *
 * @field time INT
 * @field readen tinyint NOT NULL DEFAULT 0
 *
 * @index owner,box,readen
 * @index time
 */
class R_Mdl_Msg extends O_Dao_ActiveRecord {

	public function __construct()
	{
		parent::__construct();
		$this->msg_body = new R_Mdl_User_MsgBody( );
		$this->save();
	}

	public function url()
	{
		return "http://" . O_Registry::get( "app/hosts/center" ) . "/Own/Msgs/Read-" . $this->id;
	}

	public function save()
	{
		parent::save();
		$this->msg_body->save();
	}

	public function createInboxCopy()
	{
		$in = new self( );
		$in->content = $this->content;
		$in[ "box" ] = "inbox";
		$in->owner = $this->target;
		$in->target = $this->owner;
		$in->time = $this->time;
		$in->readen = 0;
		$in->title = $this->title;
		$in->save();
		return $in;
	}

}