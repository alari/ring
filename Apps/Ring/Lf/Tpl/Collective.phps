<?php
class R_Lf_Tpl_Collective extends R_Lf_Template {
	public $groups;
	public $readers;

	public function displayContents()
	{
		echo "<h1>Коллектив сайта " . $this->site->link() . "</h1>";

		foreach($this->groups as $g) {
			echo "<h3>", $g->title, "</h3>";
			foreach ($g->getUsers() as $u) {
				echo $u->link(), " ";
			}
		}

		if (count( $this->readers )) {
			echo "<h3>Аудитория</h3>";
			foreach ($this->readers as $u)
				echo $u->link(), " ";
		}

		$this->layout()->setTitle( "Коллектив сайта" );

	}

}