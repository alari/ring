<?php
class R_Lf_Cmd_Kain extends R_Lf_Command {

	public function process()
	{
		try {
		
		$system = $this->getSite()->systems->test("urlbase", "music")->getOne();
		//$system->collections->delete();
		//$system->anonces->delete();
		
		$alb_r = O_Db_Query::get("kain_music")->select(PDO::FETCH_OBJ);
		if(0)foreach($alb_r as $a) {
			$coll = null;
			if($a->coll_id) {
				$coll = $system->collections->test("id", $a->coll_id)->getOne();
			}
			if(!$coll) {
				$coll = new R_Mdl_Site_Collection($system);
				$coll->title = $a->title_ru;
				$coll->content = $this->prepareText($a->info);
				$coll->year = $a->year;
				$coll->time = $a->date;
				$coll->save();
				O_Db_Query::get("kain_music")->field("coll_id", $coll->id)->test("id", $a->id)->update();
			}
			$cache = $a->cache;

			$tracks = explode("{sep}", $cache);
			foreach($tracks as $tr) {
				list(, , $tr) = explode(">", $tr, 3);
				list($position, $tr) = explode(".", $tr, 2);
				list(, $tr) = explode("{root}_files/mp3/", $tr, 2);
				list($file, $tr) = explode("&quot;", $tr, 2);
				list(, $tr) = explode(">", $tr, 2);
				list($title,) = explode("<", $tr, 2);
				
				$track = $coll->anonces->test("title", $title)->getOne();
				if(!$track) {
					$track = new R_Mdl_Sound_Track($system->instance);
				}
				
				$track->time = $a->date;
				$track->anonce->time = $a->date;
				$track->anonce->owner = $this->getSite()->owner;
				$track->title = $title;
				$track->anonce->position = $position;
				$track->collection = $coll;
				$track->save();
				
				copy("./static/s/kain-l.ru/tmp/".$file, $track->filePath());
				$track->save();
			}
		}
		
		} catch(Exception $e) {
			
			
			echo $e;
			exit;
		}
			
		echo "ok";
				
		$local = "./static/s/kain-l.ru/tmp";
		$md5 = <<<A
aprel/01.mp3 => 9efcb8ab22b1583f0bd645c67c61f8ae
aprel/02.mp3 => b379407388edfaa6fbc79925d87721d2
aprel/03.mp3 => c64ae3f6549ff48405cb539f5cd8a240
aprel/04.mp3 => 20d1197ffb7ad317eec23adf6296d275
aprel/05.mp3 => a723a58bff8751fdab9e0f8d7f3fcf3c
aprel/06.mp3 => aedfc9be163ebd6939e8b643e1f3aff7
aprel/07.mp3 => d73db27ac7894dbce60b2af2f6d7c5f0
aprel/08.mp3 => dc79fa2efce55a15222b7a559b4f85b8
aprel/09.mp3 => b0958a397a11fd3d54725abcecb363ad
aprel/10.mp3 => ccde949d60caa7163cf87cbe8d960fe4
aprel/11.mp3 => 8c1abe2414ac9f07d1e6a5ef586e0897
aprel/12.mp3 => b6840538bc345ae94da7024b46234107
aprel/13.mp3 => 05e4b9956de9ad6475e92576b1cd240a
chel/01.mp3 => 3b32f215aec9d690d92c69b4f5cf3ed0
chel/02.mp3 => 275f80dc922dc8f624492be5cac9d383
chel/03.mp3 => ec2e3633e83ae28315fe1d9be8d4bb36
chel/04.mp3 => 0ad4e6e42c076ee78692b6cc031683e7
chel/05.mp3 => 75130927ee32eac9f6016bc963c41c39
chel/06.mp3 => bcbcbb8fc4699462c9dd9cbe78810fa0
chel/07.mp3 => 8889396977e05a43ad649e9f1a987aa4
chel/08.mp3 => 6395de3ec04faa8da9114aec0d29f1ef
chel/09.mp3 => f966cda9456b7ed93ce8e3de9900e142
chel/10.mp3 => aeb9554700f3acfb23fdcf049b022ffc
chel/11.mp3 => 4c6e3b7f6fa2100745f7bde650a97f51
chel/12.mp3 => 1d69d8ba6aa07eea11b60ad4e858a57c
chel/13.mp3 => 5facba131c9eb05b52cfee2aafa66239
chel/14.mp3 => 96860ea3355e465c46385536c2f0ddfc
chel/15.mp3 => 2db5a246308666f7b615d7bc7b7fa098
chel/16.mp3 => 329efc82a126837cb047dd6831a83daf
chel/17.mp3 => c0401ebeeaed193dfa698b1cefd77e17
chel/18.mp3 => e4bd85c5dcfd2d5a60413ff2978e9dd8
chel/19.mp3 => 8bbd460aa4458a107fdb862b4facdd77
grave/01kChortu.mp3 => 40fa04e09769c0f481cead2227c29a07
grave/02naAsfalt.mp3 => 5bc1bc72aa7336289036aa9142f323d1
grave/03hishnik.mp3 => 63ff7414a2970f8bc4ba7b4f73b2eb61
grave/04sKryshi.mp3 => 5bc9e896039773d7e559b9b9c4296435
grave/05gostja.mp3 => 3b8746c549867ef007c1c68ba58f66fa
grave/06zhalMneVas.mp3 => ed3776c158e10d1ca59133cfb6e8b313
grave/07ecila.mp3 => 56cd8898e7b675b406b5e75946bdcdef
grave/08nadezhda.mp3 => a801367809b75f8db6afbff9abf54db5
grave/09ptichijKrik.mp3 => 54a01f04f1c7f05983eb0f4db2f54c50
grave/10korolZmey.mp3 => 5ebe2d87ba7a28d65999de68157edd98
grave/11shifr.mp3 => 6c62753dba12efa1dd6f70d747d840be
grave/12denNochSnova.mp3 => e4d9b4ca4a1751900c211f46aa78bf9a
grave/13roadToMay.mp3 => 6e3ca641dad3ca05ef9cbf33505104be
klad/01.mp3 => bbb2a3c256b1c7dfb2cdda227795b1b3
klad/02.mp3 => f74d85960339e349fd1df916211c8e56
klad/03.mp3 => ec1684007abe05dbb8bb3495dcde8305
klad/04.mp3 => 7f9cbba21f759d35283c8e1131926b64
klad/05.mp3 => 4021205feb6d02851c241d33c9a9ef73
klad/06.mp3 => 91ae9747a80152d15f39b1a110d2bd25
klad/07.mp3 => 120170f8c3e41111d905d15c8eabc4d5
klad/08.mp3 => b8dade3b5893eb9c70d1a73409377ed6
klad/09.mp3 => c4b22f43182547eecea0dbdbc525b6ed
klad/10.mp3 => 661993b715a7312f52f46954c07f358d
klad/11.mp3 => 83060fc2c5ea8b006d730f6f009f6f6d
klad/12.mp3 => 9986aa41767ad3cf982aed37fb48a205
klad/13.mp3 => 9457203185cc128363a6e8e406d87955
klad/14.mp3 => 4364f337966c47c6cebb10ee85fa32a8
klad/15.mp3 => c77788bac39d22cb49cf3f14d5e5bf1b
klad/16.mp3 => 1a801e083d444cc1670638d8ee95e75f
kv2005/01.mp3 => 2c09eb12f5ba4256267d15effcd51cf1
kv2005/02.mp3 => f164ef743327dae3c027f3580373f448
kv2005/03.mp3 => 3182710c98565846b2710e16d24640c3
kv2005/04.mp3 => b2f19b7ff960b91a51730baf6a53b9a6
kv2005/05.mp3 => dab74b2184ed582fdbcf963a4a0cca10
kv2005/06.mp3 => 12bed5c03aea479deb14c3e53a065886
kv2005/07.mp3 => 26426fd256808364a1df6fead8d63f9a
kv2005/08.mp3 => 2a754d174dd4b0c4ff2f466cd7cf6b67
kv2005/09.mp3 => 8af58362e464c0744cfed22d7a9098a1
kv2005/10.mp3 => 5f7b5d7b7d362a6670ed7d54cbbfa2d5
kv2005/11.mp3 => ce397b3c2c8bebf2090f274d97eed304
kv2005/12.mp3 => 256f8bff19df7f342b3a99e29669ec32
kv2005/13.mp3 => 39fe3aee2fe05e4b7095f411af77d209
kv2005/14.mp3 => 96b8007e14f058eabe8f494618831c60
kv2005/15.mp3 => 4df6b0407f9e3ef06a3d5e97919d79fd
kv2005/16.mp3 => f0c96ddef125fb14151805a538bfbfa9
kv2005/17.mp3 => f5a447e1f8cf3e2360f79d917a875ec5
kv2005/18.mp3 => 6f5d09170fa8001b1c80b7a726c0b1fe
kv2005/19.mp3 => e0acfaad1523863c846f07bfb8e2ec7e
kv2005/20.mp3 => 0558f46f4e8295d7809ac108b538114d
kv2005/21.mp3 => ffb5349e5319a4abf6cd365fd5b70466
kv2005/22.mp3 => 10243ca11f220404358961d57cc5c0bf
liveradio/01-vstuplenie.mp3 => 750c39f5fa7677c7c9d805e60b007a32
liveradio/02-adische.mp3 => daa85f5e9f3e1ae8fd4c1f14b0a6b1f0
liveradio/03-knyazhich.mp3 => ae46f3c43eab1ec19775b891595ed339
liveradio/04-ingvar.mp3 => 3995ee4ec8c190232d49d9461cbf85a0
liveradio/05-doroga-v-maj.mp3 => 43a0eb7dcb0c830660c607507f28f755
liveradio/06-jadom-iz-zhalosti.mp3 => e3e59108b43d24b0f4013f97ab1a9c4a
liveradio/07-nikto.mp3 => b4e2c35ea784aa99078ce794b0c44f41
liveradio/08-znanie-net.mp3 => 75045c98728a9fbb6a052b2f95803a38
liveradio/09-faust.mp3 => f0113a1e616e3b85ed4791c5fcf85cda
liveradio/10-provodnik.mp3 => 9692bd510f506a96bd9171d292488e9c
liveradio/11-otstoj.mp3 => 26dda792e8f9df33ac18b2cfdf11f242
liveradio/12-tradicija.mp3 => 8749dbbe31cd7cd8cf7afc548a7f1fee
liveradio/13-jack.mp3 => 2b328eba4f524e025fc03df2aa4c9cda
liveradio/14-shifr.mp3 => fd653ecda8ec07f4c92598e18529dfa8
liveradio/15-zveri.mp3 => 45305498ee2eadfa27c64820eaa74213
liveradio/16-pro-football.mp3 => 00861344d2f9f534e2e41b13f527f54b
out/Leti.mp3 => e4f6310443b93671d1f50236186bfa3e
out/ShB.mp3 => d189b8546fb89b3ba75d2b57f26dc6bd
out/Z-IRON.mp3 => 379c2afe2595f274ab0dc8837a17b24f
out/econ.mp3 => 5a8c2b0bf7edbdcc1a36201e54163759
out/etud.mp3 => f9703a1bf6272e4cd95e34ed48cfc88c
out/evening.mp3 => c9e0613295fbd280a5b8392f007e171d
out/faust.mp3 => 7308d5effa5da3dbdc75a86e2d49ebb9
out/fire.mp3 => 6659c310f85061f9e818a12f19ae6979
out/for.mp3 => 0b4fc7a05625c80278613949d2f5ebf8
out/jaiss.mp3 => 4591c01b3aed8ce6003f6d105b880f7f
out/jt.mp3 => 2677c3b4309961bca2172db180b856e6
out/kab.mp3 => 32c49b46e65f02860cd70b2b9529c6df
out/karl.mp3 => efbf53f1ff89676a55e86e0333e909f1
out/kozel.mp3 => 4accd2b284b47fd21fecace74b8ebf45
out/materiki.mp3 => 8a7410e3d326226c57c3398eb739f428
out/mezh.mp3 => 8571fc2d42cf4ef303f9b0d6d999acdb
out/nastr.mp3 => defb0626d7c341cf71ade444fe0f1e6a
out/nastr2.mp3 => 07ad3f16140125bbe814a94a4efb52ab
out/notlike.mp3 => 8f9e79d7247b771b90a3a2d9ea2d50d9
out/odinakovo.mp3 => bc5de34fb4c2a74d616e4cd3e76dd9dd
out/paper.mp3 => c5433b926a61ad64046126bf8a3d1c27
out/parts.mp3 => 9cfa7d5d7803fe95d69512015de0050c
out/ppd.mp3 => a4f1c9b0ca271bf35920b7ac7ff1fd6e
out/prov.mp3 => 01af85276100a6f5b0256b52cd6ba056
out/psy.mp3 => 07b8b4f274b18eb3c26d826423bfd6ae
out/railway.mp3 => a2589160912e100b723703094b1f8d63
out/ref.mp3 => a9e3c1758199a7d2a473df68b262f9cd
out/screw.mp3 => 2baea02b2427212bb85e2397be78ae48
out/smena.mp3 => c0969be4333949dcc7c1753eebe4ebeb
out/sono.mp3 => 1b536418ca254a42ad38a85dd3031b01
out/syo.mp3 => ee7adc80d3a0d2e271359df425fe25b6
out/vera.mp3 => 4021018fa70754aa0ace5817ee5d02ba
out/white_flame.mp3 => 6d3733a4f9e4a698fcd86b87537f839e
proch/01v-nachale.mp3 => c27e65cddc76fb816df321d4ebc008d1
proch/02-pust-zhivet.mp3 => feeda9b03b866c149420a00c02cbf239
proch/03-geroy-i-vesna.mp3 => 05e0e697f427bb3dd8f570d0b8a156ce
proch/04-vse-radi.mp3 => 4c8416e0f049a7a067efd45ec689aecd
proch/05net.mp3 => fdb8f0de3eb0225b5e51216ecefd367f
proch/06-poezd-galich.mp3 => eee469534f9e34c8ddea1c8573b6e67e
proch/07v-seredine.mp3 => 515e9130be1a241b44f4dec49cf8e619
proch/08kroty.mp3 => b8619e871f7da25fee8b35fffb1b2a4c
proch/09skolzko.mp3 => cedaa6c37c84a1de9cc87b6fce46a3b5
proch/10knyazhich.mp3 => 85636cc5536d3085e3236d9f4b7a150f
proch/11shaltay-boltay.mp3 => 7168310145ba1f8fc71f8f42645f944a
proch/12v-konce.mp3 => ca8998bec380f7af46b280c9fddf842a
proch/13shassi.mp3 => 812476347e28a3655142126e9f8e7295
vosh/01.mp3 => bb8a55d972fa31b2d1afcebdb4b688a6
vosh/02.mp3 => 7368e380d222c72b3cb9fa47cc2ce840
vosh/03.mp3 => 0191c58c63c5b5d87e879c1dfe02fbcf
vosh/04.mp3 => 7c07165460f827b1c5ceaa4598c5dcd8
vosh/05.mp3 => c2307242487ed70473576d022da02643
vosh/06.mp3 => 49d26fb5ca33d36a6b6ee4571058b394
vosh/07.mp3 => c086eb57018f6eecafa2a5e193973207
vosh/08.mp3 => abf334a3dc25d2739608812fce1cd466
vosh/09.mp3 => 66c1b43ecad2ac1b16f8419f5aa155c9
vosh/10.mp3 => c8e1a97d7d9bfca30959bd254262822d
vosh/11.mp3 => c313f9ed3f22c9f6b437943d944d8f2a
vosh/12.mp3 => 7ac7daeaf18ac5778d8b7a4e87dc2f6b
vosh/13.mp3 => 37e73bc436a80ee3e7ad408809791494
vosh/14.mp3 => 2c5c5bf203ced0efc4c3409828edcb25
vosh/15.mp3 => 224cb1638289ab49f1b61646ca191ff0
vosh/16.mp3 => 129ed63238d30d93001b4e29ce917106
vosh/17.mp3 => 89f817b3f5bf6f6ff5990d2a9141c8b0
vosh/18.mp3 => 8465bc7538e1b13e64101c7e67c59d0d
wasnot/01-postoj.mp3 => 451cca92033327c3810690ba4482ce37
wasnot/02-chasti.mp3 => acaf31e8bdafd2d66be44e524e86ecde
wasnot/03-ko_dnu.mp3 => 6b16ba6d366d67f3b0d34e9b9ed53601
wasnot/04-alkov.mp3 => 82045b0d489ad93f1a5bcc968a33d71e
wasnot/05-vodolaz.mp3 => 3b6bb1dd4dce385fcb14b70fadea13d9
wasnot/06-nastroenie.mp3 => c7c4733a725164671b2a0e676f210e61
wasnot/07-provodnik.mp3 => a0167eac905e87d11031fadc7bc90be6
wasnot/08-zvetochek.mp3 => e2214efefb3eee367b5adb51521bcb09
wasnot/09-la-bemol.mp3 => 706bbdf3322a430b81b9e0bd98f219f0
wasnot/10-probuzhdenje.mp3 => b2587c27b242d887a0737c8351cf0c17
wasnot/11-kliper.mp3 => 8c54d632226970b6291b6fa1b4552a08
A;
		
		$md5 = explode("\n", $md5);
		foreach($md5 as $l) {
			list($file, $hash) = explode(" => ", trim($l));
			$my_hash = is_file($local."/".$file) ? md5_file($local."/".$file) : null;
			if($my_hash != $hash) echo $file, "<br/>";
		}
	}
	
	private function prepareText($text, $class="") {
		if(!$text) return $text;
		$lines = explode("\n", $text);
		$ret = "";
		foreach($lines as $l) {
			$l = trim($l);
			$ret .= $l ? "<p".($class ? ' class="'.$class.'"' : "").">".$l."</p>" : "<br/>";
		}
		return $ret;
	}
	
	
	public function isAuthenticated() {
		return $this->getSite()->host == "kain.mirari.name";
	}
	
}