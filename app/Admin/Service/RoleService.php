<?php


namespace App\Admin\Service;

use App\Admin\Model\Menu;
use App\Admin\Model\Role;
use App\Admin\Model\RoleMenu;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class RoleService
{

    public string $primaryKey = 'role_id';

    function query(): Builder
    {
        return Role::query();
    }

    function getList($pageSize, $pageNum): array
    {
        $pagination  = $this->query()->paginate($pageSize, ['*'], 'page', $pageNum);
        $rows = [];
        foreach ($pagination->items() as $model) {
            $rows[] = getCamelAttributes($model->attributesToArray());
        }
        return [
            'rows' => $rows,
            'total' =>  $pagination->total(),
        ];
    }

    function getOne($id): array
    {
        $this->query()->find($id);
        return getCamelAttributes($this->query()->find($id)->attributesToArray());
    }

    function add($createData): bool
    {
        $createData['create_time'] = Carbon::now();
        $model = $this->query()->create($createData);
        $menuIds = $createData['menu_ids'];
        unset($createData['menu_ids']);
        if (!$model) {
            return false;
        }
        $roleMenuArray = [];
        foreach ($menuIds as $menu_id) {
            $roleMenuArray[] = [
                'role_id' => $model->role_id,
                'menu_id' => $menu_id
            ];
        }
        RoleMenu::insert($roleMenuArray);
        return true;
    }

    function edit($updateData): bool
    {
        $id = $updateData[$this->primaryKey];
        $menuIds = $updateData['menu_ids'];
        unset($updateData['menu_ids']);
        $model = $this->query()->find($id);
        $model->fill($updateData);
        if (!$model->save()) {
            return false;
        }
        $roleMenuArray = [];
        foreach ($menuIds as $menu_id) {
            $roleMenuArray[] = [
                'role_id' => $id,
                'menu_id' => $menu_id
            ];
        }
        RoleMenu::where('role_id', $id)->delete();
        RoleMenu::insert($roleMenuArray);
        return true;
    }

    function del($id): ?bool
    {
        return $this->query()->find($id)->delete();
    }

    function changeStatus($id, $status): int
    {
        return $this->query()->where('role_id', $id)->update(['status' => $status]);
    }

    function roleMenu($roleId): array
    {
        return RoleMenu::where('role_id', $roleId)->pluck('menu_id')->toArray();
    }

    function treeSelect(): array
    {
        $menuModels = Menu::query()->orderBy('order_num')->get();
        $menuData = [];
        foreach ($menuModels as $menuModel) {
            $menuData[] = [
                'id' => $menuModel->menu_id,
                'parent_id' => $menuModel->parent_id,
                'label' => $menuModel->menu_name,
            ];
        }
        return toTree($menuData);
    }

}