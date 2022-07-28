<?php


namespace App\Admin\Controller;

use App\Enums\UserStatus;
use support\Db;
use support\Request;
use support\Response;

class Dict
{

    public function getDictDataByType(Request $request, $type): Response
    {
        $rows = Db::table('sys_dict_data')
            ->where('dict_type', $type)
            ->where('status', UserStatus::NORMAL())
            ->orderBy('dict_sort')
            ->get();
        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                'default' => $row->is_default == 'Y',
                'dictCode' => $row->dict_code,
                'dictSort' => $row->dict_sort,
                'dictLabel' => $row->dict_label,
                'dictType' => $row->dict_type,
                'dictValue' => $row->dict_value,
                'listClass' => $row->dict_list_class,
                'css_class' => $row->css_class,
                'remark' => $row->remark,
                'status' => $row->status,
            ];
        }
        return successJson($data);
    }

}
