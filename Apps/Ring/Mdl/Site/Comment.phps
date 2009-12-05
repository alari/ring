<?php
/**
 * @table site_comments -show-list:callback R_Fr_Site_Comment::showCallback list
 *
 * @field owner -has one R_Mdl_User -inverse comments -preload
 *
 * @field time INT -show date
 * @field content TEXT -edit area -show -required Нужно обязательно что-то ввести -title Комментарий -check R_Mdl_Site_Comment::checkContent
 */
class R_Mdl_Site_Comment extends O_Dao_NestedSet_Node {
	const ROOT_CLASS = "R_Mdl_Site_Anonce";
	
	public function __construct(O_Dao_NestedSet_iRoot $root) {
		if (! R_Mdl_Session::isLogged ())
			throw new O_Ex_Logic ( "Cannot create comment for not logged user." );
		$this->time = time ();
		$this->owner = R_Mdl_Session::getUser ();
		parent::__construct ( $root );
	}
	
	public function notifySubscribers() {
		$owners = Array ();
		foreach ( $this->getPath () as $c ) {
			$owner = $c->owner;
			if (! $owner->email || $owner->email == $this->owner->email)
				continue;
			if (isset ( $owners [$owner->email] ))
				continue;
			$owners [$owner->email] = $owner;
		}
		$auth = null;
		if ($this->root ["owner"] != $this ["owner"] && ! array_key_exists ( $this->root->owner->email, $owners ))
			$auth = $this->root->owner;
		
		$pg_title = $this->root->title;
		$pg_url = $this->root->url ();
		$cmtr_nick = $this->owner->nickname;
		$cmtr_openid = $this->owner->identity;
		$comment_body = str_replace ( "<br />", "\n", $this->content );
		$center_host = O_Registry::get ( "app/hosts/center" );
		$msg_title = "Новый комментарий в Кольце творческих сайтов";
		
		foreach ( $owners as $email => $user ) {
			$rec_openid = $user->identity;
			$msg = <<<A
В ветке комментариев на страничку "$pg_title" ($pg_url) пользователь $cmtr_nick ($cmtr_openid) написал:
==================================
$comment_body
==================================


Вы участвовали в этой дискуссии выше и указали свой email в настройках профиля, поэтому получаете это сообщение. При авторизации Вы использовали OpenId $rec_openid.
Если Вы не хотите получать такие уведомления (с этого и других сайтов Кольца творческих сайтов) в будущем, отредактируйте Ваш профиль после авторизации по адресу: http://$center_host/
A;
			O_Mail_Service::addToQueue ( $email, "noreply@mirari.name", $msg_title, $msg );
		}
		
		if ($auth) {
			$msg = <<<A
В ветке комментариев на Вашу страничку "$pg_title" ($pg_url) пользователь $cmtr_nick ($cmtr_openid) написал:
==================================
$comment_body
==================================

Если Вы не хотите получать такие уведомления (с этого и других сайтов Кольца творческих сайтов) в будущем, отредактируйте Ваш профиль после авторизации по адресу: http://$center_host/
A;
			O_Mail_Service::addToQueue ( $auth->email, "noreply@mirari.name", "Новый отзыв на Вашей страничке", $msg );
		}
	}
	
	static public function checkContent(O_Form_Check_AutoProducer $producer) {
		$producer->setValue ( nl2br ( strip_tags ( $producer->getValue () ) ) );
	}
}