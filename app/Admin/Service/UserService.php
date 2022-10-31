<?php

namespace App\Admin\Service;

use App\Admin\Model\Menu;
use App\Admin\Model\Post;
use App\Admin\Model\Role;
use App\Admin\Model\RoleMenu;
use App\Admin\Model\User;
use App\Admin\Model\UserPost;
use App\Admin\Model\UserRole;
use App\Enums\CacheType;
use App\Enums\HttpCode;
use App\Enums\MenuType;
use App\Enums\UserStatus;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use support\Cache;

class UserService extends BaseService
{
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
        $userModel = $this->model->newQuery()->with(['dept', 'roles'])->find($uid);
        if (!$userModel) {
            throw new Exception('数据错误');
        }

        $userData    = [
            'avatar'      => $userModel->avatar,
            'email'       => $userModel->email,
            'nickName'    => $userModel->nick_name,
            'userName'    => $userModel->user_name,
            'phonenumber' => $userModel->phonenumber,
            'userId'      => $userModel->user_id,
            'remark'      => $userModel->remark,
            'sex'         => $userModel->sex,
            'status'      => $userModel->status,
            'loginIp'     => $userModel->login_ip,
            'loginDate'   => $userModel->login_date,
            'createTime'  => $userModel->create_time,
            'dept'        => [
                'ancestors' => $userModel->dept->ancestors,
                'deptName'  => $userModel->dept->dept_name,
                'leader'    => $userModel->dept->leader,
                'phone'     => $userModel->dept->phone,
                'email'     => $userModel->dept->email,
            ],
            'roles'       => [],
        ];
        $roleIds     = [];
        $roleKeys    = [];
        $permissions = [];
        $isAdmin     = false;
        foreach ($userModel->roles as $role) {
            $roleIds[] = $role->role_id;
        }
        $roleModels = Role::findMany($roleIds);
        foreach ($roleModels as $roleModel) {
            $roleKeys[] = $roleModel->role_key;
            if ($roleModel->role_key == 'admin') {
                $isAdmin = true;
            }
        }
        $userData['admin'] = $isAdmin;
        // 查找用户角色权限信息
        $menuData = $this->getMenuDataByRole($roleIds, $isAdmin);
        foreach ($menuData as $menu) {
            if ($menu->perms) {
                $permissions[] = $menu->perms;
            }
        }
        return [
            'user'        => $userData,
            'roles'       => $roleKeys,
            'permissions' => $permissions,
        ];
    }

    /**
     * 根据角色ID获取菜单
     * @param array $roleIds
     * @param bool  $all
     * @return array
     */
    function getMenuDataByRole(array $roleIds, bool $all = false): array
    {
        $returnData = [];
        $key        = CacheType::SYSTEM() . 'menu';
        $menuData   = Cache::get($key);
        if (is_null($menuData)) {
            $menuData = Menu::where(['status' => UserStatus::NORMAL(), 'visible' => UserStatus::NORMAL(),])->orderBy('order_num')->get();
            Cache::set($key, $menuData);
        }
        $menuIds = RoleMenu::query()->whereIn('role_id', $roleIds)->get()->pluck('menu_id')->toArray();
        foreach ($menuData as $menu) {
            if ($all || in_array($menu->menu_id, $menuIds)) {
                $returnData[] = $menu;
            }
        }
        return $returnData;
    }

    function getRouters($uid, $isAdmin = false): array
    {
        $roleIds = UserRole::where('user_id', $uid)->get()->pluck('role_id')->toArray();
        // 查找用户角色权限信息
        $menuModels = $this->getMenuDataByRole($roleIds, $isAdmin);
        $menuData   = [];
        foreach ($menuModels as $key => $menuModel) {
            $menu = $menuModel;
            if ($menu->menu_type == MenuType::BUTTON()) {
                continue;
            }
            $parentId  = $menu->parent_id;
            $component = 'Layout';
            if ($menu->component) {
                $component = $menu->component;
            } elseif ($parentId != 0 && $menu->is_frame == 0) {
                $component = 'InnerLink';
            } elseif ($parentId != 0 && $menu->menu_type == MenuType::FOLDER()) {
                $component = 'ParentView';
            }
            $menuData[$key] = [
                'menu_id'   => $menu->menu_id,
                'parent_id' => $menu->parent_id,
                'hidden'    => false,
                'component' => $component,
                'name'      => ucfirst($menu->path),
                'path'      => ($parentId == 0 ? '/' : '') . $menu->path,
                'redirect'  => $menu->is_frame == 0 ? $menu->path : 'noRedirect',
                'meta'      => [
                    'title'   => $menu->menu_name,
                    'icon'    => $menu->icon,
                    'noCache' => $menu->is_cache == 1,
                    'link'    => $menu->is_frame ? NULL : $menu->path,
                ],
            ];
            if ($parentId == 0) {
                $menuData[$key]['alwaysShow'] = true;
            }
            if ($menu->is_frame == 0) {
                unset($menuData[$key]['alwaysShow']);
                $menuData[$key]['path'] = $menu->path;
            }
        }
        return toTree($menuData, 'menu_id');
    }

    /**
     * @throws Exception
     */
    function add($createData): int
    {
        $postIds = $createData['post_ids'];
        $roleIds = $createData['role_ids'];
        unset($createData['post_ids']);
        unset($createData['role_ids']);
        if ($this->query()->where('user_name', $createData['user_name'])->exists()) {
            throw new Exception('用户名已存在');
        }
        if ($this->query()->where('email', $createData['email'])->exists()) {
            throw new Exception('邮件地址已存在');
        }
        if ($this->query()->where('phonenumber', $createData['phonenumber'])->exists()) {
            throw new Exception('手机号已存在');
        }

        $createData['password'] = password_hash($createData['password'], PASSWORD_DEFAULT);
        $userId                 = parent::add($createData);
        $this->delUserPost($userId);
        if ($postIds) {
            $this->addUserPost($userId, $postIds);
        }
        $this->delUserRole($userId);
        if ($roleIds) {
            $this->addUserRole($userId, $postIds);
        }
        return $userId;
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


    #[ArrayShape(['code' => "int", 'msg' => "string", 'data' => "array", 'posts' => "array", 'roles' => "array", 'postIds' => "mixed", 'roleIds' => "mixed"])]
    function one($id): array
    {
        $data     = parent::one($id);
        $postList = Post::get()->toArray();
        $roleList = Role::get()->toArray();
        array_walk($postList, function (&$value, $key) {
            $value = getCamelAttributes($value);
        });
        array_walk($roleList, function (&$value, $key) {
            $value = getCamelAttributes($value);
        });
        $postIds = UserPost::where('user_id', $id)->pluck('post_id')->toArray();
        $roleIds = UserRole::where('user_id', $id)->pluck('role_id')->toArray();
        return [
            'code'    => HttpCode::SUCCESS(),
            'msg'     => 'success',
            'data'    => $data,
            'posts'   => getCamelAttributes($postList),
            'roles'   => getCamelAttributes($roleList),
            'postIds' => $postIds,
            'roleIds' => $roleIds,
        ];
    }

    function edit($updateData): bool
    {
        $userId = $updateData['user_id'];
        parent::edit($updateData);
        if ($updateData['post_ids'] ?? false) {
            $this->delUserPost($userId);
            $this->addUserPost($userId, $updateData['post_ids']);
        }
        if ($updateData['role_ids'] ?? false) {
            $this->delUserRole($userId);
            $this->addUserRole($userId, $updateData['role_ids']);
        }
        return true;
    }

    function del($id): bool
    {
        parent::del($id);
        $this->delUserPost($id);
        $this->delUserRole($id);
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
