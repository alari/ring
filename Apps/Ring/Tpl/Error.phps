<?php
class R_Tpl_Error extends O_Tpl_Error {
	public function displayContents()
	{
		$isProduction = O_Registry::get( "app/mode" ) == "production";
		$err = $this->e->getCode();
		$msg = $this->e->getMessage();
		if (!$err || $isProduction)
			$err = 500;
		if (!$msg || $isProduction)
			$msg = "Internal server error";
		$this->layout()->setTitle( $err . ": " . $msg );
		?>
<h1>Error #<?=$err?></h1>
<strong><?=$msg?></strong>
<?
		if (!$isProduction||1) {
			?>
<p>
<pre>
<?=$this->e?>
</pre>
</p>
<?
		}
	}
}