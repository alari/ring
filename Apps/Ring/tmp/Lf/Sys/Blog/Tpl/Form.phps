<?php
class R_Lf_Sys_Blog_Tpl_Form extends R_Lf_Sys_Blog_Template {
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
			$title = "Новая запись в блоге" . ($this->blog->title ? " &laquo;" . $this->blog->title . "&raquo;" : "");
		} else {
			$title = "Правка записи в блоге" . ($this->blog->title ? " &laquo;" . $this->blog->title . "&raquo;" : "");
		}
		$this->form->setFormTitle( "<a href=\"".$this->blog->system->url()."\">".$title."</a>" );
		$this->layout()->setTitle( $title );
		$this->form->show( $this->layout() );
	}

}