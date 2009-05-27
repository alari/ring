<?php

class R_Fr_Site_System {

	/**
	 * Shows system with several anonces on frontpage
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showHomeCallback( O_Dao_Renderer_Show_Params $params )
	{
		$system = $params->record();
		/* @var $q O_Dao_Query */
		$q = $system->anonces;
		// FIXME: HARDCODE!!!
		if($system->instance instanceof R_Mdl_Afisha_System ){
			$q->test("time", time(), ">")->clearOrders()->orderBy("time");
		}

		R_Mdl_Session::setQueryAccesses( $q, $system->site );
		?>
<div class="system" id="sysid-<?=$system->urlbase?>">
<h2><?=$system->link()?></h2>
<?
		$q->limit( 5 )->show( $params->layout() )?>
</div>
<?
	}

	/**
	 * Shows system's own page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showOwnCallback( O_Dao_Renderer_Show_Params $params )
	{
		$system = $params->record();

		switch (get_class( $system->instance )) {
			case "R_Mdl_Libro" :
				$cycles = $system->collections;
				echo "<div id='coll-sort'>";
				foreach ($cycles as $cycle) {
					$anonces = $cycle->anonces;
					R_Mdl_Session::setQueryAccesses( $anonces, $system->site );
					if (!count( $anonces ))
						continue;
					?>
<div class="cycle libro" id="collid-<?=$cycle->id?>">
<h2><?=$cycle->link()?></h2>
<ul>
						<?
					foreach ($anonces as $a)
						echo "<li title=\"", $a->description, "\">", $a->link(), "</li> ";
					?>
					</ul>
</div>
<?
				}
				if (R_Mdl_Session::can( "manage site", $system->site )) {
					?>
<script type="text/javascript">
R.Collection.setSortable("#coll-sort", '.cycle.libro', '<?=$system->site->host?>');
</script>
<?
				}
				echo "</div>";
			break;

			case "R_Mdl_Sound" :
				$albums = $system->collections;
				echo "<div id='coll-sort'>";
				foreach ($albums as $album) {
					$anonces = $album->anonces;
					R_Mdl_Session::setQueryAccesses( $anonces, $system->site );
					if (!count( $anonces ))
						continue;
					?>
<div class="cycle" id="collid-<?=$album->id?>">
<h2><?=$album->link()?></h2>
<ol>
						<?
					foreach ($anonces as $a)
						echo "<li>", $a->link(), "</li> ";
					?>
					</ol>
</div>
<?
				}
				if (R_Mdl_Session::can( "manage site", $system->site )) {
					?>
<script type="text/javascript">
R.Collection.setSortable("#coll-sort", '.cycle', '<?=$system->site->host?>');
</script>
<?
				}
				echo "</div>";
			break;

			default :
				$query = $system->anonces;
				R_Mdl_Session::setQueryAccesses( $query, $system->site );
				/* @var $query O_Dao_Query */
				$query->getPaginator( array ($system->instance, "url") )->show( $params->layout(), "full" );
		}
	}

}