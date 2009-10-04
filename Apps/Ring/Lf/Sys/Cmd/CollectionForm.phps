<?php
class R_Lf_Sys_Cmd_CollectionForm extends R_Lf_Sys_Command {

	/**
	 * @var R_Mdl_Site_Collection
	 */
	public $collection;
	public $coll_id;

	public function process()
	{
		$form = $this->collection->form();

		if($form->handle()) {
			return $this->redirect($this->collection->url());
		}

		$tpl = $this->getTemplate();
		$tpl->collection = $this->collection;
		$tpl->form = $form;
		return $tpl;
	}

	public function isAuthenticated()
	{
		if (!$this->instance)
			throw new O_Ex_PageNotFound( "Instance not found.", 404 );
		$this->collection = $this->instance->system->collections->test("id", $this->coll_id)->getOne();
		if (!$this->collection)
			throw new O_Ex_PageNotFound( "Collection not found.", 404 );
		return $this->can( "read " . $this->instance->system[ "access" ], $this->getSite() )
			&& $this->can( "write " . $this->instance->system[ "access" ], $this->getSite() );
	}

}