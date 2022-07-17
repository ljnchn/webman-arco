<?php

namespace App\Admin\Service;

use App\Admin\User;
use Exception;
use support\Db;

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
    public function getUserInfo(): array
    {
        $uid = user()->getUid();
        $userModel = Db::table('sys_user')->where('user_id', $uid)->first();
        $userRole = Db::table('sys_user_role')->where(['user_id' => $uid])->first();
        if (!$userModel) {
            throw new Exception('id no found');
        }
        $roleInfo = $userRole->role_id ? $this->roleService->getRoleInfo($userRole->role_id) : [];
        return [
            'accountId' => $uid,
            'name' => $userModel->user_name,
            'avatar' => '//lf1-xgcdn-tos.pstatp.com/obj/vcloud/vadmin/start.8e0e4855ee346a46ccff8ff3e24db27b.png',
            'email' => $userModel->email,
            'job' => 'Software Engender',
            'jobName' => '开发工程师',
            'organization' => 'Backend',
            'organizationName' => '后端',
            'location' => 'beijing',
            'locationName' => '北京',
            'introduction' => '相见不如怀念',
            'personalWebsite' => '',
            'phone' => $userModel->phonenumber,
            'registrationDate' => '2013-05-10 12:10:00',
            'certification' => 1,
            'role_id' => $userRole->role_id,
            'dept_id' => $userModel->dept_id,
            'role' => $roleInfo['role_key'] ?? 'user',
        ];
    }

    /**
     * @throws Exception
     */
    public function getUserMenu(): array
    {
        $userInfo = $this->getUserInfo();
        $roleId = $userInfo['role_id'];
        $role = $userInfo['role'];
        $userMenu = [];
        if ($roleId) {
            $menuList = $this->roleService->getRoleMenuList([$roleId]);
            return $menuList;
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
