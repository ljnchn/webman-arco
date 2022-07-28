<?php

namespace App\Admin\Service;

use App\Enums\MenuType;
use App\Enums\DictType;
use App\Enums\UserStatus;
use Exception;
use support\Db;

class UserService
{

    /**
     * @param $uid
     * @return array
     * @throws Exception
     */
    public function getUserInfo($uid): array
    {
        $userModels = Db::table('sys_user')
            ->leftJoin('sys_dept', 'sys_user.dept_id', '=', 'sys_dept.dept_id')
            ->leftJoin('sys_user_role', 'sys_user.user_id', '=', 'sys_user_role.user_id')
            ->leftJoin('sys_role', 'sys_user_role.role_id', '=', 'sys_role.role_id')
            ->where('sys_user.user_id', $uid)
            ->where('sys_user.status', UserStatus::NORMAL())
            ->where('sys_user.del_flag', UserStatus::NORMAL())
            ->get();
        if (!$userModels) {
            throw new Exception();
        }
        $userData = [
            'admin' => false,
            'avatar' => $userModels[0]->avatar,
            'email' => $userModels[0]->email,
            'nickName' => $userModels[0]->nick_name,
            'userName' => $userModels[0]->user_name,
            'phonenumber' => $userModels[0]->phonenumber,
            'userId' => $userModels[0]->user_id,
            'remark' => $userModels[0]->remark,
            'sex' => $userModels[0]->sex,
            'status' => $userModels[0]->status,
            'loginIp' => $userModels[0]->login_ip,
            'login_date' => $userModels[0]->login_date,
            'dept' => [
                'ancestors' => $userModels[0]->ancestors,
                'deptName' => $userModels[0]->dept_name,
                'leader' => $userModels[0]->leader,
                'phone' => $userModels[0]->phone,
                'email' => $userModels[0]->email,
            ],
            'roles' => [],
        ];
        $roleIds = [];
        $roleKeys = [];
        foreach ($userModels as $userModel) {
            $roleIds[] = $userModel->role_id;
            $roleKeys[] = $userModel->role_key;
            $userData['roles'][] = [
                'admin' => $userModel->role_key == 'admin',
                'roleName' => $userModel->role_name,
                'roleKey' => $userModel->role_key,
                'dataScope' => $userModel->data_scope,
            ];
        }
        $permissions = [];
        // 查找用户角色权限信息
        $menuModels = Db::table('sys_role_menu')
            ->leftJoin('sys_menu', 'sys_role_menu.menu_id', '=', 'sys_menu.menu_id')
            ->where('sys_menu.visible', MenuType::STATUS_NORMAL())
            ->whereIn('sys_role_menu.role_id', $roleIds)
            ->orderBy('sys_menu.order_num')
            ->get();

        foreach ($menuModels as $menuModel) {
            if ($menuModel->perms) {
                $permissions[] = $menuModel->perms;
            }
        }

        return [
            'user' => $userData,
            'permissions' => $permissions,
            'roles' => $roleKeys,
        ];
    }

    public function getRouters($uid): array
    {
        $menuModels = Db::table('sys_user_role')
            ->leftJoin('sys_role_menu', 'sys_user_role.role_id', '=', 'sys_role_menu.role_id')
            ->leftJoin('sys_menu', 'sys_role_menu.menu_id', '=', 'sys_menu.menu_id')
            ->where('sys_user_role.user_id', $uid)
            ->where('sys_menu.visible', MenuType::STATUS_NORMAL())
            ->whereIn('sys_menu.menu_type', [MenuType::FOLDER(), MenuType::MENU()])
            ->orderBy('sys_menu.order_num')
            ->get();

        $menuData = $this->getMenuChildren($menuModels, 0);
        // 暂时递归三层
        foreach ($menuData as $key => $item) {
            $children = $this->getMenuChildren($menuModels, $item['menu_id']);
            if ($children) {
                $menuData[$key]['children'] = $children;
                foreach ($menuData[$key]['children'] as &$item2) {
                    $children = $this->getMenuChildren($menuModels, $item2['menu_id']);
                    if ($children) {
                        $item2['children'] = $children;
                    }
                }
            }
        }
        return $menuData;
    }

    public function getMenuChildren(&$menuModels, $parentId): array
    {
        $children = [];
        foreach ($menuModels as $key => $model) {
            if ($model->parent_id == $parentId) {
                $index = count($children);
                $children[$index] = [
                    'menu_id' => $model->menu_id,
                    'hidden' => false,
                    'component' => $model->component ?? 'Layout',
                    'name' => ucfirst($model->path),
                    'path' => ($parentId == 0 ? '/' : '') . $model->path,
                    'redirect' => $model->is_frame == 0 ? $model->path : 'noRedirect',
                    'meta' => [
                        'title' => $model->menu_name,
                        'icon' => $model->icon,
                        'noCache' => $model->is_cache == 1,
                        'link' => $model->is_frame ? null : $model->path,
                    ],
                ];
                if ($parentId == 0) {
                    $children[$index]['alwaysShow'] = true;
                }
                if ($model->is_frame == 0) {
                    unset($children[$index]['alwaysShow']);
                    $children[$index]['path'] = $model->path;
                }
                unset($menuModels[$key]);
            }
        }
        return $children;
    }

}
