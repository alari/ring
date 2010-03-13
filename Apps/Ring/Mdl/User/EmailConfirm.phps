<?php
/**
 * @table user_email_confirm
 *
 * @field owner -has one R_Mdl_User -inverse email_confirm
 * @field hash_key VARCHAR(32) NOT NULL
 * @field time INT
 *
 * @index time
 * @index hash_key -unique
 */
class R_Mdl_User_EmailConfirm extends O_Dao_ActiveRecord {

	static private $ignore = Array();

	public function __construct(R_Mdl_User $user)
	{
		parent::__construct();
		$this->time = time();
		$this->owner = $user;
		$this->hash_key = md5(microtime(true).$user->email);
		$this->save();
	}

	public function sendEmail() {
		$link = "http://".O_Registry::get("app/hosts/center")."/confirm-email?hash=".$this->hash_key;

		$msg_body = <<<MSG
Здравствуйте.

Вы зарегистрировались, авторизовались или изменили адрес электронной почты в Кольце Творческих Сайтов. Чтобы подтвердить неизвестный системе адрес, пожалуйста, пройдите по следующей ссылке:

     $link

Если Вы считаете это сообщение ошибкой, просто проигнорируйте его.
MSG;

		$msg = new O_Mail_Message($this->owner->email, "noreply@mirari.name", "Подтверждение адреса электронной почты", $msg_body);

		$msg->send();
	}

	static public function checkConfirm($hash_key) {
		$o = static::getQuery()->test("hash_key", $hash_key)->getOne();
		if($o && $o->owner) {
			$ow = $o->owner;
			$ow->email_confirmed = 1;
			$ow->save();
			$o->delete();
			return true;
		}
		return false;
	}

	static public function gc() {
		static::getQuery()->test("time", time()-86400*3, "<")->delete();
	}

	static public function eventListener($fieldValue, O_Dao_ActiveRecord $user, $event) {
		if(in_array($user->id, self::$ignore)) return true;
		$user->email_confirmed = 0;
		$o = new self($user);
		$o->sendEmail();
	}

	static public function ignore(R_Mdl_User $user) {
		self::$ignore[] = $user->id;
	}

}