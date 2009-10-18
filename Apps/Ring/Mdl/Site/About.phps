<?php
/**
 * @table site_about -edit:submit Сохранить изменения
 * @field site -has one R_Mdl_Site -inverse about_page
 * @field title varchar(255) NOT NULL DEFAULT 'О сайте' -edit -show container h1 -required Название странички обязательно -title Заголовок страницы
 * @field content mediumtext -edit wysiwyg -check htmlPurify -show -required Напишите что-нибудь! -title
 */
class R_Mdl_Site_About extends O_Dao_ActiveRecord {

}