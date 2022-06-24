<?php

namespace App\Service;

use App\Model\SysUser;
use Exception;

class UserService
{

    /**
     * @Inject
     * @var RoleService
     */
    private $roleService;

    /**
     * @throws Exception
     */
    public function getUserInfo($uid): array
    {
        $userModel = SysUser::query()->find($uid);
        if (!$userModel) {
            throw new Exception('id no found');
        }
        $roleInfo = $userModel->role_id ? $this->roleService->getRoleInfo($userModel->role_id) : [];
        return [
            'accountId' => $userModel->id,
            'name' => $userModel->nick_name,
            'avatar' => '//lf1-xgcdn-tos.pstatp.com/obj/vcloud/vadmin/start.8e0e4855ee346a46ccff8ff3e24db27b.png',
            'email' => $userModel->email,
            'job' => 'frontend',
            'jobName' => '前端艺术家',
            'organization' => 'Frontend',
            'organizationName' => '前端',
            'location' => 'beijing',
            'locationName' => '北京',
            'introduction' => '人潇洒，性温存',
            'personalWebsite' => '',
            'phone' => $userModel->phone,
            'registrationDate' => '2013-05-10 12:10:00',
            'certification' => 1,
            'role_id' => $userModel->role_id,
            'dept_id' => $userModel->dept_id,
            'role' => $roleInfo['role_key'] ?? 'user',
        ];
    }

    /**
     * @throws Exception
     */
    public function getUserMenu($uid): array
    {
        $userInfo = $this->getUserInfo($uid);
        $roleId = $userInfo['role_id'];
        $role = $userInfo['role'];
        $userMenu = [];
        if ($roleId) {
            $menuList = $this->roleService->getRoleMenuList($roleId);
            foreach ($menuList as $menu) {
                $children = [];
                foreach ($menu['items'] as $item) {
                    $children[] = [
                        'path' => $item['path'],
                        'name' => $item['name'],
                        'meta' => [
                            'locale' => 'menu.' . $menu['perms'] . '.' . $item['perms'],
                            'requiresAuth' => true,
                            'roles' => [$role],
                        ],
                    ];
                }
                $userMenu[] = [
                    'path' => $menu['path'],
                    'name' => $menu['name'],
                    'meta' => [
                        'locale' => 'menu.' . $menu['perms'],
                        'requiresAuth' => true,
                        'roles' => [$role],
                        'icon' => $menu['icon'],
                        // 'order' => 1,
                    ],
                    'children' => $children,
                ];
            }
        }
        return $userMenu;
    }
}
