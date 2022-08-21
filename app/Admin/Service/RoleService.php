<?php


namespace App\Admin\Service;

use App\Admin\Model\Menu;
use App\Admin\Model\Role;
use App\Admin\Model\RoleMenu;
use Carbon\Carbon;

class RoleService
{
    use TraitService;

    public function __construct()
    {
        $this->model = new Role();
    }

    function add($createData): bool
    {
        $createData['create_time'] = Carbon::now();
        $model                     = $this->query()->create($createData);
        $menuIds                   = $createData['menu_ids'];
        unset($createData['menu_ids']);
        if (!$model) {
            return false;
        }
        $this->addRoleMenu($model->role_id, $menuIds);
        return true;
    }

    function edit($updateData): bool
    {
        $id      = $updateData[$this->model->getKeyName()];
        $menuIds = $updateData['menu_ids'];
        unset($updateData['menu_ids']);
        $model = $this->query()->find($id);
        $model->fill($updateData);
        if (!$model->save()) {
            return false;
        }
        $this->delRoleMenu($id);
        $this->addRoleMenu($id, $menuIds);
        return true;
    }

    function del($id): ?bool
    {
        $this->delRoleMenu($id);
        return $this->model->find($id)->delete();
    }

    function changeStatus($roleId, $status): bool
    {
        $key = $this->model->getKeyName();
        $this->query()->where($key, $roleId)->update(['status' => $status]);
        return true;
    }

    function addRoleMenu($roleId, $menuIds): void
    {
        $roleMenuArray = [];
        foreach ($menuIds as $menu_id) {
            $roleMenuArray[] = [
                'role_id' => $roleId,
                'menu_id' => $menu_id
            ];
        }
        RoleMenu::insert($roleMenuArray);
    }

    function delRoleMenu($roleId): void
    {
        RoleMenu::where('role_id', $roleId)->delete();
    }

    function roleMenu($roleId): array
    {
        return RoleMenu::where('role_id', $roleId)->pluck('menu_id')->toArray();
    }

    function treeSelect(): array
    {
        $menuModels = Menu::orderBy('order_num')->get();
        $menuData   = [];
        foreach ($menuModels as $menuModel) {
            $menuData[] = [
                'id'        => $menuModel->menu_id,
                'parent_id' => $menuModel->parent_id,
                'label'     => $menuModel->menu_name,
            ];
        }
        return toTree($menuData);
    }

}