<?php


namespace App\Admin\Controller;

use App\Admin\Model\Post;
use App\Admin\Model\Role;
use App\Admin\Service\UserService;
use support\Request;
use support\Response;

class UserController extends BaseController
{

    public function __construct()
    {
        $this->service     = new UserService();
        $this->customParam = ['userName', 'deptId', 'phonenumber'];

        parent::__construct();
    }

    public function info(Request $request): Response
    {
        $postList = Post::get()->toArray();
        $roleList = Role::get()->toArray();
        array_walk($postList, function (&$value, $key) {
            $value = getCamelAttributes($value);
        });
        array_walk($roleList, function (&$value, $key) {
            $value = getCamelAttributes($value);
        });
        return json([
            'code'  => 200,
            'msg'   => 'success',
            'posts' => getCamelAttributes($postList),
            'roles' => getCamelAttributes($roleList)
        ]);
    }

    public function resetPwd(Request $request): Response
    {
        $userId   = $request->input('userId');
        $password = $request->input('password');
        if ($this->service->resetPwd($userId, $password)) {
            return successJson();
        }
        return failJson();
    }
}
