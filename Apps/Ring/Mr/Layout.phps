<?php
class R_Mr_Layout extends R_Layout {

	/**
	 * Main body
	 *
	 */
	public function displayBody()
	{		?><center>
<?

		$this->tpl->displayContents();
		?></center>

<div style="float:right">
<?$this->showCounter();?>
</div>
<?
		$this->setTitle( "Кольцо творческих сайтов" );
	}
}