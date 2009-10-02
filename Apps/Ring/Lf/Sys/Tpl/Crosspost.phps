<?php
class R_Lf_Sys_Tpl_Crosspost extends R_Lf_Sys_Template {
	public $crossposts;
	public $available_services;

	public function displayContents()
	{
		foreach ($this->crossposts as $c) echo $c->url;
		echo "<hr/>";
		foreach($this->available_services as $s) echo $s->blog_url;
	?>
<p><b>������������</b> � ��� ����������� �������� ���������� ������� � ������ ������ ������������. ����� �������, �������� ������ � ������, �� ������ ������������� ������� �� �� ������ ������. ��������� �������� <a href="http://mirari.name/������������" target="_blank">�����</a>.</p>
<?php
	}

}