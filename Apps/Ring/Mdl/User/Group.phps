<?php
/**
 * @table user_groups
 *
 * @field users -has many _User -inverse groups
 * @field site -has one _Site -inverse groups
 *
 * @field type TINYINT DEFAULT NULL
 * @field flag TINYINT
 * @field title TINYTEXT
 *
 * @index site,type -unique
 * @index type
 * @index site,flag -unique
 */
class R_Mdl_User_Group extends O_Dao_ActiveRecord {
	const TYPE_CUSTOM = NULL;
	const TYPE_BANNED = 1;
	const TYPE_FOLLOWERS = 2;
	const TYPE_AUTH_FRIENDS = 3;
	const TYPE_COMM_MEMBERS = 3;
	const TYPE_COMM_LEADERS = 4;
	const TYPE_COMM_REQUESTS = 5;
	const TYPE_COMM_INVITATIONS = 6;

	static private function getNewGroupFlag(R_Mdl_Site $site, $type) {
		switch($type) {
			case self::TYPE_AUTH_FRIENDS:
			case self::TYPE_COMM_MEMBERS:
				return 1;
			case self::TYPE_COMM_LEADERS:
				return 2;
			case self::TYPE_COMM_REQUESTS:
				return 4;
			case self::TYPE_COMM_INVITATIONS:
				return 8;
			case self::TYPE_CUSTOM:
				$max = $site->groups->getFunc("flag", "MAX");
				if($max < (1<<7)) return $max << 1;
				return 0;
			default:
				return 0;
		}
	}

	static private function getNewGroupTitle($type, $isComm) {
		switch($type){
			case 1: return "Бан-лист";
			case 2: return "Аудитория";
			case 3: return $isComm?"Участники":"Друзья";
			case 4: return "Руководство";
			case 5: return "Заявки на вступление";
			case 6: return "Приглашения в группу";
			default: return "Новая группа";
		}
	}

	public function __construct(R_Mdl_Site $site, $type, $title=null) {
		$this->flag = self::getNewGroupFlag($site, $type);
		$this->type = $type;
		$this->title = $title ? $title : self::getNewGroupTitle($type, $site["type"]==R_Mdl_Site::TYPE_COMM);
		$this->site = $site;
		parent::__construct();
	}

	static public function createSiteGroups(R_Mdl_Site $site, R_Mdl_User $owner) {
		new self($site, self::TYPE_BANNED);
		new self($site, self::TYPE_FOLLOWERS);
		if($site["type"]==R_Mdl_Site::TYPE_COMM) {
			new self($site, self::TYPE_COMM_MEMBERS);
			$leaders = new self($site, self::TYPE_COMM_LEADERS);
			$leaders->users[] = $owner;
			new self($site, self::TYPE_COMM_REQUESTS);
			new self($site, self::TYPE_COMM_INVITATIONS);
		} else {
			new self($site, self::TYPE_AUTH_FRIENDS);
			$site->owner = $owner;
			$site->save();
		}
	}

	static public function addFollower(R_Mdl_Site $site, R_Mdl_User $user) {
		$site->groups->test("type", self::TYPE_FOLLOWERS)->getOne()->users[] = $user;
		if($user->site instanceof R_Mdl_Site && $site->owner instanceof R_Mdl_User) {
			$user->site->groups->test("type", self::TYPE_AUTH_FRIENDS)->getOne()->users[] = $site->owner;
		}
	}

	static public function addFriend(R_Mdl_User $friender, R_Mdl_User $target) {
		if($target->site instanceof R_Mdl_Site) {
			self::addFollower($target->site, $friender);
		} elseif($friender->site instanceof R_Mdl_Site) {
			$friender->site->groups->test("type", self::TYPE_AUTH_FRIENDS)->getOne()->users[] = $target;
		}
	}

	static public function removeFriend(R_Mdl_User $unfriender, R_Mdl_User $target) {
		if($target->site instanceof R_Mdl_Site) {
			self::removeFollower($target->site, $unfriender);
		} elseif($unfriender->site instanceof R_Mdl_Site) {
			$unfriender->site->groups->test("type", self::TYPE_AUTH_FRIENDS)->getOne()->users->remove($target);
		}
	}

	static public function removeFollower(R_Mdl_Site $site, R_Mdl_User $user) {
		$site->groups->test("type", self::TYPE_FOLLOWERS)->getOne()->users->remove($user);
		if($user->site instanceof R_Mdl_Site && $site->owner instanceof R_Mdl_User) {
			$user->site->groups->test("type", self::TYPE_AUTH_FRIENDS)->getOne()->users->remove($site->owner);
		}
	}
}