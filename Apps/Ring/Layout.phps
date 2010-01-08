<?php
class R_Layout extends O_Html_Layout {
	/**
	 * Current or user-related site
	 *
	 * @var R_Mdl_Site
	 */
	protected $site;

	private $sape;
	private $linkfeed;

	public function setMetaDescription( $args )
	{
		parent::addMeta( "description", join( " - ", $args ) );
	}

	public function setMetaKeywords( $args )
	{
		$args = array_merge( $args,
				array ("творчество", "проза", "стихи", "музыка", "песни", "авторы", "публикации",
						"читать", "слушать", "интересно") );
		parent::addMeta( "keywords", join( ", ", $args ) );
	}

	/**
	 * Returns sape client
	 *
	 * @return SAPE_client
	 */
	public function sape()
	{
		if ($this->sape) {
			return $this->sape;
		}
		if (!defined( '_SAPE_USER' )) {
			define( '_SAPE_USER', '78e3b4251484822d768fa71f69ef1d4a' );
		}
		require_once './' . _SAPE_USER . '/sape.php';
		$this->sape = new SAPE_client( array ("charset" => "UTF-8", "multi_site" => true) );
		return $this->sape;
	}

	/**
	 * Returns linkfeed client
	 *
	 * @return LinkfeedClient
	 */
	public function linkfeed()
	{
		if ($this->linkfeed) {
			return $this->linkfeed;
		}
		define( 'LINKFEED_USER', '22b3dae89af246203ae6df09b8b0200f1540c101' );

		require_once './' . LINKFEED_USER . '/linkfeed.php';

		$this->linkfeed = new LinkfeedClient( array ("charset" => "UTF-8", "multi_site" => true) );
		return $this->linkfeed;
	}

	/**
	 * redefine O_Html_Layout::displayDoctype()
	 *
	 */
	protected function displayDoctype()
	{
		?>
<?=

		'<?xml version="1.0" encoding="UTF-8"?>'?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
	}

	protected function showNotice()
	{
		if (isset( $_SESSION[ "notice" ] )) {
			?>
<div id="notice" onclick="$(this).fade('out')"><?=$_SESSION[ "notice" ]?></div>
<?
			unset( $_SESSION[ "notice" ] );
		}
	}

	protected function userMenu()
	{
		if (R_Mdl_Session::isLogged()) {

			$site = $this->site;
			if (!$site)
				$site = O_Registry::get( "app/current/site" );
			if (!$site)
				$site = R_Mdl_Session::getUser()->site;
			if ($site instanceof R_Mdl_Site) {
				$systems = $site->getSystems();
				?>
<p><b><a href="<?=$site->url()?>"><?=$site->title?></a></b></p>
<ul><?
				foreach ($systems as $sys) {
					?>
<li><?=$sys->link() . (R_Mdl_Session::can( "write " . $sys[ "access" ], $site ) ? " &nbsp; <small><a href=\"" . $sys->url( "form" ) . "\">Добавить</a></small>" : "")?></li>
<?
				}
				?>
				<li><i><a href="<?=$site->url( "comments" )?>">Комментарии на сайте</a></i></li>
				<?
				if (!R_Mdl_Session::can( "manage site", $site ) && R_Mdl_Session::can(
						"manage styles", $site )) {
					?>
<li><a href="<?=$site->url( "admin/site-view" )?>">Редактировать
	оформление</a></li>
				<?
				}
				?>
				</ul>

<p><i><a href="http://<?=O_Registry::get( "app/hosts/center" )?>/">Взглянуть
из центра</a></i></p>
<?
				if (R_Mdl_Session::can( "manage site", $site )) {
					?>
<p><b>Настройки сайта</b></p>
<ul>
	<li><a href="<?=$site->url( "admin/site" )?>">Общие настройки</a></li>
	<li><a href="<?=$site->url( "admin/tags" )?>">Метки</a></li>
	<li><a href="<?=$site->url( "admin/about" )?>">Страница &laquo;О
	сайте&raquo;</a></li>
	<li><a href="<?=$site->url( "admin/systems" )?>">Список систем</a></li>
	<?
					if (R_Mdl_Session::can( "manage styles", $site )) {
						?>
	<li><a href="<?=$site->url( "admin/site-view" )?>">Редактировать
	оформление</a></li><?
					}
					?>

	<?
				}
				if (R_Mdl_Session::can( "crosspost", $site )) {
					?><li><a href="<?=$site->url( "admin/crossposting" )?>">Кросспостинг</a></li><?
				}
				?>
</ul>
<?

			}

			$new_msgs = R_Mdl_Session::getUser()->msgs_own->test( "readen", 0 )->getFunc();

			?>
<p><b><a
	href="http://<?=O_Registry::get( "app/hosts/center" )?>/own/msgs/">Внутренняя
почта</a></b></p>
<ul>
<?
			if (R_Mdl_Session::can( "write msgs" )) {
				?><li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/own/msgs/write">Написать</a></li><?
			}
			?>
<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/own/msgs/">Входящие<?=($new_msgs ? " <b>(+$new_msgs)</b>" : "")?></a></li>
</ul>

<p><b><a href="<?=R_Mdl_Session::getUser()->url()?>">Ваш профиль</a></b></p>
<ul>
	<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/own/edit-profile">Редактировать
	профиль</a></li>

	<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/own/favorites">Избранное</a></li>
<?
			if (R_Mdl_Session::getUser()->site) {
				?>
<li><a href="<?=R_Mdl_Session::getUser()->site->url()?>">Сайт <?=R_Mdl_Session::getUser()->site->title?></a></li>
<?
			}
			?>
</ul>
<?
		}

		if (R_Mdl_Session::can( "manage roles" ) || R_Mdl_Session::can( "manage users" ) || R_Mdl_Session::can("create community")) {
			?>
<p><b>Управление</b></p>
<ul>
<?
			if (R_Mdl_Session::can( "manage roles" )) {
				?>
<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/admin/roles">Настройки
	ролей</a></li>
<?
			}
			if (R_Mdl_Session::can( "manage users" )) {
				?>
<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/admin/user">Новый
	пользователь</a></li>
<?
			}
		if (R_Mdl_Session::can( "create community" )) {
				?>
<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/admin/comm">Новое сообщество</a></li>
<?
			}
			?>
</ul>
<?
		}
	}

	protected function showCounter()
	{
		if (O_Registry::get( "app/mode" ) != "production")
			return;
		?>
<?=

		(R_Mdl_Session::isLogged() ? "" : $this->linkfeed()->return_links())?>
<div id="site_cnt"><!--LiveInternet counter--> <script
	type="text/javascript">document.write("<a href='http://www.liveinternet.ru/click;ring' target=_blank><img src='http://counter.yadro.ru/hit;ring?t26.1;r" + escape(document.referrer) + ((typeof(screen)=="undefined")?"":";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?screen.colorDepth:screen.pixelDepth)) + ";u" + escape(document.URL) +";i" + escape("Жж"+document.title.substring(0,80)) + ";" + Math.random() + "' border=0 width=88 height=15 alt='' align='absmiddle' title='LiveInternet: показано число посетителей за сегодня. <?=round( microtime( true ) - O_Registry::get( "start-time" ), 4 )?>'><\/a>")</script>
<!--/LiveInternet--></div>
<div id="site_sp"><?=(R_Mdl_Session::isLogged() ? "" : $this->sape()->return_links())?></div>
<?
echo "<!--", print_r(O_Registry::get("profiler"),1), "-->";
echo "<!--", print_r(array_keys(O_Registry::get("fw/classmanager/loaded")),1), "-->";
	}

	/**
	 * Login box / logged abilities
	 *
	 */
	protected function loginBox()
	{
		if (R_Mdl_Session::isLogged()) {
			?>
<p>Привет,
<?=R_Mdl_Session::getUser()->link()?>! <a
	href="<?=O_UrlBuilder::get( "openid/logout" )?>">Выход</a></p>
<p><a href="javascript:void(0)" onclick="R.UserMenu.toggle()">Возможности</a></p>
<p><a
	href="http://<?=O_Registry::get( "app/hosts/center" )?>/own/friends">Друзья</a>
&nbsp; <small><a
	href="http://<?=O_Registry::get( "app/hosts/center" )?>/own/friends/list">Кто</a></small></p>
<div id="user-menu"><?
			$this->userMenu()?></div>
<?
		} else {
			$this->openidBox();
		}
	}

	protected function openidBox()
	{
		?>
<div id="openid">
<form method="POST"
	action="http://<?=O_Registry::get( "app/hosts/center" )?>/openid/login">

<input type="text" name="openid_identifier" class="openid-blur"
	value="OpenID"
	onfocus="this.className='openid-focus';this.value=this.value=='OpenID'?'':this.value"
	onblur="this.value = this.value ? this.value : 'OpenID';if(this.value=='OpenID') this.className = 'openid-blur'"
	class="openid-blur" /> <input type="submit" value="Вход"
	id="openid-signup" /> <span>(например, логин.livejournal.com)</span> <input
	type="hidden" name="openid_action" value="login" /> <input
	type="hidden" name="redirect"
	value="http://<?=$_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ]?>" />
</form>
</div>
<?
	}

}