<?php

namespace App\Admin\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use support\Model;

/**
 * @property integer $role_id 角色ID(主键)
 * @property integer $menu_id 菜单ID(主键)
 */
class RoleMenu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_role_menu';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'menu_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['*'];

    /**
     * @return HasOne
     */
    public function menu(): HasOne
    {
        return $this->hasOne(Menu::class, 'menu_id');
    }
}
