<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/29
 * Time: 下午7:53
 */

namespace app\admin\model;


use think\Db;

class Bookcase extends Common
{

    public $oneToMany = [
        'drawer' => 'pid'
    ];

    public function add($data)
    {
        if (empty($data)) {
            return returnJson(602, 400, '添加参数不能为空');
        }

        if (empty($data['num'])) {
            return returnJson(602, 400, '抽屉数量不能为空');
        } elseif (!is_numeric($data['num'])) {
            return returnJson(602, 400, '抽屉数为整数');
        }

        $data['create_user'] = session('id');
        $data['modify_user'] = session('id');
        $data['number'] = 'ttbk'.strtotime('now').$this->create_key(3);

        $this->startTrans();
        try {
            //添加主表
            $result = $this->validate(true)->allowField($this->addallow)->validate(true)->save($data);
            if ($result == false)
                return returnJson(603, 400, $this->getError());
            $this->table('drawer')->insertAll($this->createBooks(['pid' => $this->getAttr('id')], (int)$data['num']));
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            return returnJson(603, 400, $e->getMessage());
        }  catch (\Error $e) {
            $this->rollback();
            return returnJson(603, 400, $e->getMessage());
        }
        return returnJson(702, 200, $this->toArray());
    }

    private function createBooks($data, $num)
    {
        $drawer = [];
        $no = 'dra';
        $time = strtotime('now');
        $date = date('Y-m-d H:i:s', $time);
        for ($i = 0; $i < $num; $i++) {
            $tmp = $data;
            $tmp['number'] = $no.$time.$this->num2str($i, 3);
            $tmp['create_user'] = session('id');
            $tmp['modify_user'] = session('id');
            $tmp['create_time'] = $date;
            $tmp['modify_time'] = $date;
            array_push($drawer, $tmp);
        }
        return $drawer;
    }

    private function create_key($length)
    {
        $randkey = '';
        for ($i = 0; $i < $length; $i++) {
            $randkey .= chr(mt_rand(48, 57));
        }
        return $randkey;
    }

    private function num2str($num,$length)
    {
        $num_str = (string)$num;
        $num_strlength = count($num_str);
        if ($length > $num_strlength) {
            $num_str=str_pad($num_str,$length,"0",STR_PAD_LEFT);
        }
        return $num_str;
    }

    public function getByArea($data, $page = 1, $limit = 10)
    {
        if (empty($data['search'])) {
            return returnJson(607, 400, '搜索不能为空');
        } else {
            $area = $this->table('area')
                ->where('number',$data['search'])
                ->whereOr('name', $data['search'])
                ->find();
            if (is_null($area)) {
                return returnJson(608, 400, '没有此区域');
            }

            $result = $this->where('area', $area->getAttr('id'))
                ->paginate($limit, false, ['page' => $page]);

            return returnJson(701, 200, $result);
        }
    }

    public function getManage($data, $returnType = 1)
    {
        if (empty($data['id'])) {
            return returnJson(607, 400, '缺少ID');
        }

        $info = [];
        $drawer = new Drawer;
        $info['isnventory'] = $drawer->where('pid', $data['id'])
            ->where('state', 2)->count();

        $info['catalog'] = $drawer->alias('a')
            ->where('a.pid', $data['id'])
            ->join('books bs', 'a.bid=bs.id', 'LEFT')
            ->field('distinct bs.name,bs.author,bs.press')
            ->select();

        $info['kong'] = $drawer->where('pid', $data['id'])
            ->where('state', 1)->count();

        if ($returnType == 1)
            return returnJson(701, 200, $info);
        elseif ($returnType == 2)
            return $info;
    }

    public function getInfo($id, $returnType = 1)
    {
        $info = [];
        $info['books'] = $drawer->alias('a')
            ->where('pid', $id)
            ->field('a.state, a.num')
            ->join('books bs', 'a.bid=bs.id', 'LEFT')
            ->join('book b', 'b.id=bs.id', 'LEFT')
            ->field('b.name, b.isbn')
            ->join('btype bt', 'bt.id=b.type', 'LEFT')
            ->field('bt.name')
            ->select();

        if ($returnType == 1)
            return returnJson(701, 200, $info);
        elseif ($returnType == 2)
            return $info;
    }
}