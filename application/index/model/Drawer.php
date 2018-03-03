<?php

namespace app\index\model;

use think\Model;

class Drawer extends Common
{

    //

    protected function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }


    /**
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function books()
    {
        return $this->hasMany('books', 'pid')->select();
    }

    public function getDrawPos()
    {

    }
}
