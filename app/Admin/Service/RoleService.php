<?php

namespace App\Service;

use App\Model\SysMenu;
use App\Model\SysRole;
use App\Model\SysRoleMenu;
use support\Cache;

class RoleService
{

    /**
     * 根据角色ID获取角色信息
     *
     * @param int $roleId
     * @return array
     */
    public function getRoleInfo(int $roleId): array
    {
        $roleList = $this->getRoleList();
        $roleInfo = [];
        foreach ($roleList as $role) {
            if ($role['id'] == $roleId) {
                $roleInfo = $role;
            }
        }
        return $roleInfo;
    }

    /**
     * 获取角色列表
     *
     * @return array
     */
    public function getRoleList(): array
    {
        $cacheKey = $this->getCacheKey('list');
        if (!$roleList = Cache::get($cacheKey)) {
            $roleModels = SysRole::query()->orderBy('role_sort')->get();
            $roleList = [];
            foreach ($roleModels as $roleModel) {
                $roleList[] = [
                    'id' => $roleModel->id,
                    'role_key' => $roleModel->role_key,
                    'role_name' => $roleModel->role_name,
                    'role_desc' => $roleModel->role_desc,
                ];
            }
            Cache::set($cacheKey, $roleList);
        }
        return $roleList;
    }

    /**
     * 获取用户显示的菜单
     * @param $roleId
     * @return array
     */
    public function getShowMenu($roleId): array
    {
        $menuList = $this->getRoleMenuList($roleId);
        $roleMenu = [];
        foreach ($menuList as $menu) {
            $children = [];
            foreach ($menu['items'] as $item) {
                $children[] = [
                    'path' => $item['path'],
                    'name' => $item['name'],
                    'meta' => [
                        'locale' => 'menu.' . $menu['perms'] . '.' . $item['perms'],
                        'requiresAuth' => true,
                    ],
                ];
            }
            $roleMenu[] = [
                'path' => $menu['path'],
                'name' => $menu['name'],
                'meta' => [
                    'locale' => 'menu.' . $menu['perms'],
                    'requiresAuth' => true,
                    'icon' => $menu['icon'],
                    // 'order' => 1,
                ],
                'children' => $children,
            ];
        }
        return $roleMenu;
    }

    /**
     * 获取角色菜单列表
     *
     * @param int $roleId
     * @return array
     */
    public function getRoleMenuList(int $roleId): array
    {
        $cacheKey = $this->getCacheKey('menu:' . $roleId);
        if (!$menuList = Cache::get($cacheKey)) {
            $menuIds = SysRoleMenu::query()->where('role_id', $roleId)->get()->pluck('menu_id')->toArray();
            $menuModels = SysMenu::query()->where('status', 1)->whereIn('type', [1, 2])->orderBy('sort')->find($menuIds);
            $menuData = [];
            foreach ($menuModels as $menuModel) {
                $menuData[$menuModel->pid][] = [
                    'id' => $menuModel->id,
                    'name' => $menuModel->name,
                    'path' => $menuModel->path,
                    'perms' => $menuModel->perms,
                    'icon' => $menuModel->icon,
                    'type' => $menuModel->type,
                ];
            }
            $menuList = [];
            foreach ($menuModels as $menuModel) {
                if ($menuModel->type == 1) {
                    $menuList[] = [
                        'id' => $menuModel->id,
                        'name' => $menuModel->name,
                        'path' => $menuModel->path,
                        'perms' => $menuModel->perms,
                        'icon' => $menuModel->icon,
                        'type' => $menuModel->type,
                        'items' => $menuData[$menuModel->id] ?? [],
                    ];
                }
            }
            Cache::set($cacheKey, $menuList);
        }
        return $menuList;
    }

    /**
     * 清除角色列表的缓存
     *
     * @param $type
     * @return bool
     */
    public function clearRoleListCache($type): bool
    {
        return Cache::delete($this->getCacheKey($type));
    }

    /**
     * 获取菜单缓存的 key
     *
     * @param $type
     * @return string
     */
    public function getCacheKey($type): string
    {
        return sprintf("role:%s:%s", $type, request()->tid);
    }
}
