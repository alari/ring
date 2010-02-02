<?php
class R_Lf_Tpl_Collective extends R_Lf_Template {
	public $leaders;
	public $members;
	public $readers;

	public function displayContents()
	{
		echo "<h1>Коллектив сообщества " . $this->site->link() . "</h1>";

		if (count( $this->leaders )) {
			echo "<h3>Руководство</h3><ul>";
			foreach ($this->leaders as $u) {
				echo "<li>", $u->link(), "</li>";
			}
			echo "</ul><hr/>";
		}

		if (count( $this->members )) {
			echo "<h3>Участники</h3>";
			foreach ($this->members as $u)
				echo $u->link(), " ";
			echo "<hr/>";
		}
		if (count( $this->readers )) {
			echo "<h3>Читатели</h3>";
			foreach ($this->readers as $u)
				echo $u->link(), " ";
		}

		$this->layout()->setTitle( "Коллектив сайта" );

	}

}