<?php
class R_Ctr_Tpl_Admin_Roles extends O_Acl_Admin_Tpl {

	/**
	 * Dictionary of phrases used in template
	 *
	 * @var array
	 */
	protected $phrases = Array ("allow" => "Разрешить", "deny" => "Запретить", "clear" => "Наследовать",
								"ed_role" => "Настройки роли", "action" => "Действие",
								"parent" => "Родительская роль", "no_parent" => "нет родителя",
								"submit" => "Сохранить изменения", "reset" => "Сбросить",
								"choose_role" => "Выберите роль из списка", "success" => "Изменения сохранены.",
								"failure" => "Ошибки при сохранении роли.", "add_new" => "Добавить роль",
								"sbm_new" => "Так", "set_visitor" => "Установить как роль гостя");
}