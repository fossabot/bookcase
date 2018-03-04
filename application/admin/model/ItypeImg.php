<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/3/3
 * Time: 下午4:44
 */

namespace app\admin\model;


class ItypeImg extends Common
{
    public function del($data, $softdel = true)
    {
        if (!isset($data['ids']) && empty($data['ids']))
        {
            return returnJson(604, 400, '缺少删除参数');
        }

        $img = BanImg::get($data['ids']);
        if (!is_null($img)) {
            unlink($img->getAttr('path'));
            $img->delete();
        }
        return returnJson(703, 200, '删除成功');
    }
}