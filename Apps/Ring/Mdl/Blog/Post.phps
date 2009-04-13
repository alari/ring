<?php
/**
 * @table blog_post
 * @field owner -has one R_Mdl_User -inverse blog_posts
 * @field anonce -owns one R_Mdl_Blog_Anonce -inverse post
 *
 * @field time int NOT NULL
 * @field title VARCHAR(255) -show-loop linkInContainer h2 -show container h1 -edit -title Заголовок записи
 * @field content MEDIUMTEXT -show -edit wysiwyg -required Текст записи необходим -check htmlPurify -title
 *
 * @field tags -has many R_Mdl_Tag -inverse blog_posts -edit selectRelationBox -title Метки
 * @field blog -has one R_Mdl_Blog -inverse posts --edit
 *
 * @field access ENUM('public','protected','private','disable') NOT NULL DEFAULT 'public'
 *
 * @field system -alias blog.system
 *
 * @index blog,access,time
 */
class R_Mdl_Blog_Post extends O_Dao_NestedSet_Root {
	const NODES_CLASS = "R_Mdl_Blog_Comment";

	public function __construct()
	{
		$this->time = time();
		parent::__construct();
	}

	public function save()
	{
		parent::save();
		if (!$this->anonce) {
			$this->anonce = new R_Mdl_Blog_Anonce( );
		}
		$this->anonce->blog = $this->blog;
		$this->anonce->time = $this->time;
		$split_content = strip_tags( $this->content );
		if (!$this->title)
			$this->title = substr( $split_content, 0, 32 );
		$this->anonce->description = substr( $split_content, 0, 255 );
		$this->anonce->title = $this->title;
		$this->anonce->access = $this->access;
		if (count( $this->tags ))
			$this->anonce->tags = $this->tags;
		else
			$this->anonce->tags->removeAll();
		parent::save();
		$this->anonce->save();
		return true;
	}

	public function getSystemId()
	{
		return $this->blog[ "system" ];
	}

	public function url()
	{
		return $this->anonce->url();
	}

}