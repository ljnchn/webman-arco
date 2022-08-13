<?php

namespace app\Admin\Models;

/**
 *
 */
class Menu extends CModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_menu';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'menu_id';

    protected $guarded = ['menu_id'];
}
