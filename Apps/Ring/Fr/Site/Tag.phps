<?php

class R_Fr_Site_Tag {
	const TAG_LEVELS = 7;

	/**
	 * Shows tag cloud for all given tags
	 *
	 * @param O_Db_Query $q
	 * @param R_Mdl_Site_System $system
	 * @param unknown_type $weightField
	 */
	static public function showCloud( O_Db_Query $q, R_Mdl_Site_System $system=null, $weightField = "weight" )
	{
		$max_w = 0;
		$min_w = -1;
		foreach($q as $tag) {
			if($tag["weight"] > $max_w) $max_w = $tag[$weightField];
			if($min_w == -1 || $min_w > $tag["weight"]) $min_w = $tag[$weightField];
		}
		if(!$max_w) $max_w = 1;
		if($min_w < 1) $min_w = 0;
		echo "<div class=\"tags-cloud\">";
		foreach($q as $tag) {
			$level = round((($tag[$weightField] - $min_w)/($max_w-$min_w))*self::TAG_LEVELS);
			echo "<span class='tag-$level'>";
			echo $tag->link($system);
			echo "</span>";
		}
		echo "</div>";
	}
}