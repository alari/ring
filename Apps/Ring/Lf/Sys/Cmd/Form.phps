<?php
class R_Lf_Sys_Cmd_Form extends R_Lf_Sys_Command {
	public $creative_id;
	public $creative;

	public function process()
	{
		$form = new O_Dao_Renderer_FormProcessor( );
		$form->setClass( constant( get_class( $this->instance ) . "::CREATIVE_CLASS" ) );
		
		$form->setRelationQuery( "tags", $this->getSite()->tags, "title", true );
		
		$form->setRelationQuery( "collection", $this->instance->system->collections, "title" );
		
		O_Registry::set( "app/current/system", $this->instance->system );
		
		if ($this->creative_id) {
			$form->setActiveRecord( $this->creative );
			$form->setType( "up" );
		} else {
			$form->setCreateMode( $this->instance );
			$form->setType( "new" );
		}
		if ($form->handle()) {
			$this->creative = $form->getActiveRecord();
			$this->creative->owner = R_Mdl_Session::getUser();
			$this->creative->save();
			if ($this->getParam( "tag_new" )) {
				$new_tag = $this->getSite()->tags->test( "title", $this->getParam( "tag_new" ) )->getOne();
				if (!$new_tag) {
					$new_tag = new R_Mdl_Site_Tag( $this->getSite() );
					$new_tag->title = $this->getParam( "tag_new" );
					$new_tag->save();
				}
				
				if ($new_tag)
					$this->creative->tags[] = $new_tag;
			}
			if (!$this->creative_id) {
				$crosspost = $this->getParam( "crosspost" );
				if (count( $crosspost )) {
					foreach ($this->getSite()->crosspost_services as $serv) {
						if (!in_array( $serv->id, $crosspost ))
							continue;
						new R_Mdl_Site_Crosspost( $this->creative->anonce, $serv );
					}
				}
			} else {
				$this->creative->anonce->crossposts->field( "last_update", time() )->update();
			}
			return $this->redirect( $this->creative->url() );
		}
		
		$tpl = $this->getTemplate();
		$tpl->form = $form;
		$tpl->isCreateMode = !$this->creative_id;
		return $tpl;
	}

	public function isAuthenticated()
	{
		if (!$this->instance) {
			return false;
		}
		if ($this->creative_id) {
			$this->creative = $this->instance->getCreative( $this->creative_id );
			if (!$this->creative) {
				throw new O_Ex_Redirect( "/" );
			}
			return $this->can( "read " . $this->instance->system[ "access" ], 
					$this->creative->anonce ) && $this->can( 
					"write " . $this->instance->system[ "access" ], $this->creative->anonce );
		}
		return $this->can( "read " . $this->instance->system[ "access" ], $this->getSite() ) && $this->can( 
				"write " . $this->instance->system[ "access" ], $this->getSite() );
	}

}