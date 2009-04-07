<?php
/**
 * @table tags
 * @field site -has one R_Mdl_Site -inverse tags
 * @field title VARCHAR(255) NOT NULL
 * @field description VARCHAR(255)
 * @field weight int NOT NULL DEFAULT 0
 *
 * @field blog_posts -has many R_Mdl_Blog_Post -inverse tags
 * @field blog_anonces -has many R_Mdl_Blog_Anonce -inverse tags
 *
 * @index site,weight
 * @index weight
 */
class R_Mdl_Tag extends O_Dao_ActiveRecord {

}

?>