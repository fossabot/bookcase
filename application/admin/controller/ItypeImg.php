<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/3/3
 * Time: 下午4:43
 */

namespace app\admin\controller;


use think\Request;

class ItypeImg extends Common
{
    protected $model;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->model = new \app\admin\model\ItypeImg();
    }

    public function add(Request $request)
    {
        $file = $request->file('img');
        $data = [];
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                $data['path'] = ROOT_PATH . 'public' . DS . 'uploads' . DS . $info->getSaveName();
                $data['imgurl'] = DS . DS . $request->host(). DS . 'images' . DS . $info->getSaveName();
            } else {
                return returnJson(609, 400, $info->getError());
            }
            return $this->model->add($data);
        } else {
            return returnJson(609, 400, '没有上传图片');
        }
    }

    public function delete(Request $request)
    {
        return $this->model->del($request->param());
    }
}