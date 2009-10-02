<?php
class R_Lf_Sys_Tpl_Form extends R_Lf_Sys_Template {
	/**
	 * Form processor
	 *
	 * @var O_Dao_Renderer_FormProcessor
	 */
	public $form;
	public $isCreateMode;

	public function displayContents()
	{
		if ($this->isCreateMode) {
			$title = $this->instance->addFormTitle();
			$params = new O_Dao_Renderer_Edit_Params( "crosspost", null,
					array ("displayField" => "blog_url", "multiply" => true,
								"query" => $this->getSite()->crosspost_services) );
			ob_start();
			O_Dao_Renderer_Edit_Callbacks::selectRelationBox( $params );
			$this->form->injectHtmlAfter( "tags", ob_get_clean() );
		} else {
			$title = $this->instance->editFormTitle();
		}
		$this->form->setFormTitle(
				"<a href=\"" . $this->instance->system->url() . "\">" . $title . "</a>" );
		$this->layout()->setTitle( $title );
		$this->form->show( $this->layout() );
	}

}