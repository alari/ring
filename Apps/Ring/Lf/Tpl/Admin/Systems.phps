<?php
class R_Lf_Tpl_Admin_Systems extends R_Lf_Template {

	public $systems;
	public $types;
	public $accesses;

	public function __construct()
	{
		$this->accesses = R_Mdl_Site_System::getAccesses();
	}

	public function systemEditFragment( R_Mdl_Site_System $sys )
	{
		if (!$sys instanceof R_Mdl_Site_System) {
			?>
<strong>Система не найдена. Проверьте авторизацию.</strong>
<?
			return;
		}
		?>
<form method="post" action="<?=$_SERVER["REQUEST_URI"]?>">
<center>Название ссылки: <input type="text" name="title"
	value="<?=htmlspecialchars( $sys->title )?>" /> &nbsp;&nbsp; Уровень
доступа: <select name="access"><?
		foreach ($this->accesses as $k => $v) {
			?><option
		<?=($sys->access == $v ? ' selected="yes"' : '')?>><?=$v?></option><?
		}
		?></select> &nbsp;&nbsp; <input type="submit" value="Сохранить"
	id="sys-sbm-<?=$sys->id?>" /> <input type="hidden" name="action"
	value="edit-system" /><input type="hidden" name="sys"
	value="<?=$sys->id?>" /></center>
</form>
<script type="text/javascript">
 tmp = function(){
	 el = $('sys-sbm-<?=$sys->id?>');
		if(!el) {
			tmp.delay(50);
			return;
		}
 el.addEvent("click", function(e){
	 e.stop();
 	$(this).disabled = true;

 	new Request.JSON({url:'<?=$_SERVER["REQUEST_URI"]?>',onSuccess:function(response){
		if(response.status == 'SUCCEED') {
			if(response.refresh == 1) {
				window.location.reload(true);
			} else {
				el.getParent().set('html', response.show);
			}
		} else {
			alert(response.error);
			el.getElement('input[type=submit]').disabled = false;
		}
 	 }}).post(el.getParent().getParent());
 });
 };
 tmp();
 </script>
<?
	}

	public function displayContents()
	{
		?>
<fieldset><legend>Системы сайта &ndash; элементы главного меню</legend>
<table width="100%">
	<?
		foreach ($this->systems as $sys) {
			?>
<tr>
		<th><a href="<?=$sys->url()?>"><?=$sys->title?></a></th>
		<td><?=$sys->getType()?></td>
		<td><small><a href="javascript:void(0)"
			onclick="R.AjaxFragment.toggle('sys-ed-<?=$sys->id?>', {url:'<?=$_SERVER['REQUEST_URI']?>',data:{sys:<?=$sys->id?>,action:'system-fragment'},evalScripts:true}, 'height', 40)">Доступ
		и имя ссылки</a></small></td>
		<td><small><a href="<?=$sys->url( "SystemAdmin" )?>">Остальные
		настройки</a></small></td>
	</tr>
	<tr>
		<td colspan="5">
		<div id="sys-ed-<?=$sys->id?>"></div>
		<hr />
		</td>
	</tr>
	<?
		}
		?>
</table>
</fieldset>

<form method="post">
<fieldset><legend>Создать новую систему</legend>
<table>
	<tr>
		<td>Название ссылки:</td>
		<td><input type="text" name="title" /></td>
	</tr>
	<tr>
		<td>Фрагмент адреса (папка на сайте):</td>
		<td><input type="text" name="urlbase" /></td>
	</tr>
	<tr>
		<td>Тип системы:</td>
		<td><select name="type">
		<?
		foreach ($this->types as $t => $title) {
			?><option value="<?=$t?>"><?=$title?></option><?
		}
		?>
		</select></td>
	</tr>
	<tr>
		<td>Уровень доступа:</td>
		<td><select name="access"><?
		foreach ($this->accesses as $v) {
			?><option><?=$v?></option><?
		}
		?></select></td>
	</tr>

	<tr>
		<td colspan="2" align="right" /><input type="submit"
			value="Создать новую систему" /></td>
	</tr>
</table>
<input type="hidden" name="action" value="create-system" /></fieldset>
</form>
<?
	}

}