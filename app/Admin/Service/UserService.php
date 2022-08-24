<?php

namespace App\Admin\Service;

use App\Admin\Model\RoleMenu;
use App\Admin\Model\User;
use App\Admin\Model\UserPost;
use App\Admin\Model\UserRole;
use App\Enums\MenuType;
use App\Enums\UserStatus;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class UserService
{

    use TraitService;

    function __construct()
    {
        $this->model = new User();
    }

    /**
     * @param $uid
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['user' => "array", 'permissions' => "array", 'roles' => "array"])]
    function getUserInfo($uid): array
    {
        $userModels = User::query()
            ->leftJoin('sys_dept', 'sys_user.dept_id', '=', 'sys_dept.dept_id')
            ->leftJoin('sys_user_role', 'sys_user.user_id', '=', 'sys_user_role.user_id')
            ->leftJoin('sys_role', 'sys_user_role.role_id', '=', 'sys_role.role_id')
            ->where('sys_user.user_id', $uid)
            ->where('sys_user.status', UserStatus::NORMAL())
            ->get();
        if (!$userModels) {
            throw new Exception();
        }
        $userData = [
            'admin'       => false,
            'avatar'      => $userModels[0]->avatar,
            'email'       => $userModels[0]->email,
            'nickName'    => $userModels[0]->nick_name,
            'userName'    => $userModels[0]->user_name,
            'phonenumber' => $userModels[0]->phonenumber,
            'userId'      => $userModels[0]->user_id,
            'remark'      => $userModels[0]->remark,
            'sex'         => $userModels[0]->sex,
            'status'      => $userModels[0]->status,
            'loginIp'     => $userModels[0]->login_ip,
            'login_date'  => $userModels[0]->login_date,
            'dept'        => [
                'ancestors' => $userModels[0]->ancestors,
                'deptName'  => $userModels[0]->dept_name,
                'leader'    => $userModels[0]->leader,
                'phone'     => $userModels[0]->phone,
                'email'     => $userModels[0]->email,
            ],
            'roles'       => [],
        ];
        $roleIds  = [];
        $roleKeys = [];
        $isAdmin  = false;
        foreach ($userModels as $userModel) {
            if ($userModel->role_key == 'admin') {
                $isAdmin = true;
            }
            $roleIds[]           = $userModel->role_id;
            $roleKeys[]          = $userModel->role_key;
            $userData['roles'][] = [
                'admin'     => $userModel->role_key == 'admin',
                'roleName'  => $userModel->role_name,
                'roleKey'   => $userModel->role_key,
                'dataScope' => $userModel->data_scope,
            ];
        }
        $permissions = [];
        if ($isAdmin) {
            $permissions[] = '*';
        }
        // 查找用户角色权限信息
        $menuModels = RoleMenu::query()
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
            'user'        => $userData,
            'permissions' => $permissions,
            'roles'       => $roleKeys,
        ];
    }

    function getRouters($uid): array
    {
        $userInfo = user()->getInfo();
        $query    = UserRole::query()
            ->leftJoin('sys_role_menu', 'sys_user_role.role_id', '=', 'sys_role_menu.role_id')
            ->leftJoin('sys_menu', 'sys_role_menu.menu_id', '=', 'sys_menu.menu_id');

        if (!in_array('*', $userInfo['permissions'])) {
            $query->where('sys_user_role.user_id', $uid);
        }
        $menuModels = $query->where('sys_menu.visible', MenuType::STATUS_NORMAL())
            ->whereIn('sys_menu.menu_type', [MenuType::FOLDER(), MenuType::MENU()])
            ->orderBy('sys_menu.order_num')
            ->get();

        $menuData = [];
        foreach ($menuModels as $key => $model) {
            $parentId  = $model->parent_id;
            $component = 'Layout';
            if ($model->component) {
                $component = $model->component;
            } elseif ($parentId != 0 && $model->is_frame == 0) {
                $component = 'InnerLink';
            } elseif ($parentId != 0 && $model->menu_type == MenuType::FOLDER()) {
                $component = 'ParentView';
            }
            $menuData[$key] = [
                'menu_id'   => $model->menu_id,
                'parent_id' => $model->parent_id,
                'hidden'    => false,
                'component' => $component,
                'name'      => ucfirst($model->path),
                'path'      => ($parentId == 0 ? '/' : '') . $model->path,
                'redirect'  => $model->is_frame == 0 ? $model->path : 'noRedirect',
                'meta'      => [
                    'title'   => $model->menu_name,
                    'icon'    => $model->icon,
                    'noCache' => $model->is_cache == 1,
                    'link'    => $model->is_frame ? null : $model->path,
                ],
            ];
            if ($parentId == 0) {
                $menuData[$key]['alwaysShow'] = true;
            }
            if ($model->is_frame == 0) {
                unset($menuData[$key]['alwaysShow']);
                $menuData[$key]['path'] = $model->path;
            }
        }
        return toTree($menuData, 'menu_id');
    }

    function userAdd($createData): bool
    {
        $postIds = $createData['post_ids'];
        $roleIds = $createData['role_ids'];
        unset($createData['post_ids']);
        unset($createData['role_ids']);
        // todo 检查用户名，邮箱，手机号
        $createData['password'] = password_hash($createData['password'], PASSWORD_DEFAULT);
        $userId                 = $this->add($createData);
        $this->delUserPost($userId);
        if ($postIds) {
            $this->addUserPost($userId, $postIds);
        }
        $this->delUserRole($userId);
        if ($roleIds) {
            $this->addUserRole($userId, $postIds);
        }
        return true;
    }

    function delUserPost($userId)
    {
        return UserPost::where('user_id', $userId)->delete();
    }

    function addUserPost($userId, $postIds): bool
    {
        $insertData = [];
        foreach ($postIds as $postId) {
            $insertData[] = [
                'user_id' => $userId,
                'post_id' => $postId
            ];
        }
        return UserPost::insert($insertData);
    }

    function delUserRole($userId)
    {
        return UserRole::where('user_id', $userId)->delete();
    }

    function addUserRole($userId, $roleIds): bool
    {
        $insertData = [];
        foreach ($roleIds as $roleId) {
            $insertData[] = [
                'user_id' => $userId,
                'role_id' => $roleId
            ];
        }
        return UserRole::insert($insertData);
    }

    function userEdit($updateData): bool
    {
        $userId = $updateData['user_id'];
        $this->edit($updateData);
        $this->delUserPost($userId);
        if ($updateData['post_ids']) {
            $this->addUserPost($userId, $updateData['post_ids']);
        }
        $this->delUserRole($userId);
        if ($updateData['role_ids']) {
            $this->addUserRole($userId, $updateData['role_ids']);
        }
        return true;
    }

    function userDel($userId): bool
    {
        $this->del($userId);
        $this->delUserPost($userId);
        $this->delUserRole($userId);
        return true;
    }

    function resetPwd($userId, $password): bool
    {
        $this->query()->where('user_id', $userId)->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        return true;
    }

}
