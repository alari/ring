<?php
class R_Lf_Tpl_Admin_Tags extends R_Lf_Template {

	public $tags;
	public $form;

	public function tagEditFragment()
	{
		if (!$this->form) {
			?>
<strong>Метка не найдена. Проверьте авторизацию.</strong>
<?
			return;
		}

		$this->form->show();
	}

	public function displayContents()
	{
		?>
<fieldset><legend>Метки на сайте</legend>
<table width="100%">
	<?
		foreach ($this->tags as $tag) {
			?>
<tr>
		<th><?=$tag->link()?></th>
		<td><small><a href="javascript:void(0)"
			onclick="R.AjaxFragment.toggle('tag-ed-<?=$tag->id?>', {url:'<?=$_SERVER['REQUEST_URI']?>',data:{tag:<?=$tag->id?>,action:'tag-fragment'},evalScripts:true})">Править
		метку</a></small></td>
		<td><small><a href="javascript:void(0)"
			onclick="if(confirm('Вы уверены, что хотите удалить эту метку?')) R.AjaxFragment.toggle('tag-ed-<?=$tag->id?>', {url:'<?=$_SERVER['REQUEST_URI']?>',data:{tag:<?=$tag->id?>,action:'tag-delete'},evalScripts:true})">Удалить</a></small></td>
	</tr>
	<tr>
		<td colspan="3">
		<div id="tag-ed-<?=$tag->id?>"></div>
		<hr />
		</td>
	</tr>
	<?
		}
		?>
</table>
</fieldset>

<?
		if ($this->form)
			$this->form->show( $this->layout() );
	}

}