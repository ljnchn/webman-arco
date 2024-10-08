<?php


namespace App\Admin\Controller;

use App\Admin\Model\Post;
use App\Admin\Model\Role;
use App\Admin\Model\UserPost;
use App\Admin\Service\UserService;
use App\Enums\HttpCode;
use Exception;
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
        array_walk($postList, function (&$value) {
            $value = getCamelAttributes($value);
        });
        array_walk($roleList, function (&$value) {
            $value = getCamelAttributes($value);
        });
        return json([
            'code'  => HttpCode::SUCCESS(),
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

    /**
     * @throws Exception
     */
    public function profileView(): Response
    {
        $userInfo = $this->service->getUserInfo(user()->getUid());

        $postGroup = '';
        $postId = UserPost::where('user_id', user()->getUid())->first()?->post_id;
        if ($postId) {
            $postGroup = Post::find($postId)->post_name;
        }
        return json([
            'code'      => HttpCode::SUCCESS(),
            'msg'       => 'success',
            'postGroup' => $postGroup,
            'roleGroup' => '',
            'data'      => $userInfo['user'],
        ]);
    }
}
