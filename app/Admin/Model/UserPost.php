<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $user_id 用户ID(主键)
 * @property integer $post_id 岗位ID(主键)
 */
class UserPost extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_user_post';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'post_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    
}
