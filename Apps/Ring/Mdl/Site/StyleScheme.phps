<?php
/**
 * @table site_stylescheme
 *
 * @field sites -has many R_Mdl_Site -inverse style_scheme
 * @field data TEXT
 * @field title VARCHAR(256)
 * @field color_back VARCHAR(15)
 * @field color_text VARCHAR(15)
 */
class R_Mdl_Site_StyleScheme extends O_Dao_ActiveRecord {
	public function setData(Array $data) {
		$this->color_back = $data [3];
		$this->color_text = $data [6];
		$this->data = serialize ( $data );
	}
	
	public function getData() {
		return unserialize ( $this->data );
	}
	
	public function getCssText() {
		$c = $this->getData ();
		if (! is_array ( $c ) || count ( $c ) != 10) {
			return "";
		}
		return self::tmpCssFromArray ( $c );
	}
	
	static public function tmpCssFromArray(Array $c) {
		ob_start ();
		?>

#head,#head a:hover,.system h2 a:hover,h1,.anonce{color:<?=$c [1]?>}
#main-menu{border-bottom-color:<?=$c [1]?>}
#foot{background-color:<?=$c [1]?>}

.system h2 a{color:<?=$c [2]?>}
.system h2{border-bottom-color:<?=$c [2]?>}
.comm-ava img{border-color:<?=$c [2]?>}
.comm{border-left:1px solid <?=$c [2]?>;border-top:1px solid <?=$c [2]?>}

#main-menu,#rcol,.anonce,.anonce strong{background-color:<?=$c [3]?>}

body{color:<?=$c [4]?>}

#head,.system h2{background-color:<?=$c [5]?>}
#main-menu a,.anonce strong a{color:<?=$c [5]?>}
#rcol{border-color:<?=$c [5]?>}

#foot{border-top-color:<?=$c [6]?>}
#foot,hr{color:<?=$c [6]?>}

h2,h3,h4,body a,#rcol li a,#head a{color:<?=$c [7]?>}
#main-menu{border-bottom-color:<?=$c [7]?>}

#openid span{color:<?=$c [8]?>}

#main-menu a,#rcol a,#foot a{color:<?=$c [9]?>}

#wrap,.anonce .cnt{background-color:<?=$c [10]?>}

<?
		return ob_get_clean ();
	}

}