<?php
class R_Mr_Cmd_Edit extends R_Command {
	public function process() {
		$form = new O_Dao_Renderer_FormProcessor();
		$form->setClass("R_Mdl_Info_Page");
		echo "a";
		$page = O_Registry::get("app/current/page");
		if($page instanceof R_Mdl_Info_Page ) {
			$form->setActiveRecord($page);
		} else {
			$form->setCreateMode(urldecode(substr(O_Registry::get("app/env/process_url"), 5)));
		}
		echo "b";
		if($form->handle()) {
			return $this->redirect($form->getActiveRecord()->url());
		}
		echo "c";
		$tpl = $this->getTemplate();
		$tpl->form = $form;
		echo "d";
		return $tpl;
	}
	
}