<?php
class R_Lf_Tpl_Admin_Site extends R_Lf_Template {
	
	/**
	 * Form processor for site
	 *
	 * @var O_Dao_Renderer_FormProcessor
	 */
	public $form;

	public function displayContents()
	{
		if ($this->form) {
			$this->form->setFormTitle( "Настройки сайта как целого" );
			$this->form->setSubmitButtonValue( "Сохранить изменения" );
			$this->form->show( $this->layout() );
		}
	}

	public function displayNav()
	{
		?>
<ul>
	<li><a href="<?=$this->url( "Admin/Site" )?>">Настройки сайта</a></li>
	<li><a href="<?=$this->url( "Admin/SiteView" )?>">Редактировать
	оформление</a></li>
</ul>
<?
	}

}

?>