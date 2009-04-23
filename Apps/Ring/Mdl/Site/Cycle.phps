<?php
/**
 * @table tags
 * @field system -has one R_Mdl_Site_System -inverse cycles
 *
 * @field title VARCHAR(64) NOT NULL -edit -required -title Название цикла
 * @field description VARCHAR(255) -edit -title Описание или расшифровка
 * @field position int NOT NULL DEFAULT 0
 *
 * @field anonces -has owns R_Mdl_Site_Anonce -inverse cycle
 *
 * @index system,position
 */
class R_Mdl_Site_Cycle extends O_Dao_ActiveRecord {

}