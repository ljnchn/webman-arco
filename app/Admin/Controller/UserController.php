<?php


namespace App\Admin\Controller;

use App\Admin\Model\Post;
use App\Admin\Model\Role;
use App\Admin\Model\UserPost;
use App\Admin\Model\UserRole;
use App\Admin\Service\UserService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use support\Request;
use support\Response;

class UserController
{

    private UserService $service;
    use TraitController;

    public function __construct()
    {
        $this->service = new UserService();
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
            'code' => 200,
            'msg' => 'success',
            'posts' => getCamelAttributes($postList),
            'roles' => getCamelAttributes($roleList)
        ]);
    }

    public function one(Request $request, $id): Response
    {
        $postList = Post::get()->toArray();
        $roleList = Role::get()->toArray();
        array_walk($postList, function (&$value, $key) {
            $value = getCamelAttributes($value);
        });
        array_walk($roleList, function (&$value, $key) {
            $value = getCamelAttributes($value);
        });
        $data = $this->service->one($id);
        $postIds = UserPost::where('user_id', $id)->pluck('post_id');
        $roleIds = UserRole::where('user_id', $id)->pluck('role_id');

        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $data,
            'posts' => getCamelAttributes($postList),
            'roles' => getCamelAttributes($roleList),
            'postIds' => $postIds,
            'roleIds' => $roleIds,
        ]);
    }

    public function add(Request $request): Response
    {
        $creatData = [];
        foreach ($request->post() as $key => $item) {
            $creatData[Str::snake($key)] = $item;
        }
        $creatData['create_by']   = user()->getInfo()['user']['userName'];
        $creatData['create_time'] = Carbon::now();
        if ($this->service->userAdd($creatData)) {
            return successJson();
        } else {
            return failJson();
        }
    }

    public function edit(Request $request): Response
    {
        $updateData = [];
        foreach ($request->post() as $key => $item) {
            $updateData[Str::snake($key)] = $item;
        }
        $updateData['update_by']   = user()->getInfo()['user']['userName'];
        $updateData['update_time'] = Carbon::now();
        if ($this->service->userEdit($updateData)) {
            return successJson();
        } else {
            return failJson();
        }
    }

    public function del(Request $request, $id): Response
    {
        if ($this->service->userDel($id)) {
            return successJson();
        }
        return failJson();
    }

    public function resetPwd(Request $request): Response
    {
        $userId = $request->input('userId');
        $password = $request->input('password');
        if ($this->service->resetPwd($userId, $password)) {
            return successJson();
        }
        return failJson();
    }
}
