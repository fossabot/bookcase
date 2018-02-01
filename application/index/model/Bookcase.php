<?php

namespace app\index\model;


class Bookcase extends Common
{


    protected function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }


    /**
     * 查找某城市下所有书柜
     *
     * @param $city
     *
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function casesList($city, $lat, $lng)
    {
        if ( ! empty($city) || $city != null) {
            $data = $this->where('city', $city)->select()->toArray();
            foreach ($data as &$value) {
                $distance          = (2 * 6378.137 * ASIN(
                        SQRT(
                            POW(
                                SIN(
                                    3.1415926535898 * ($lat - $value['lat'])
                                    / 360
                                ), 2
                            ) + COS(3.1415926535898 * $lat / 180) * COS(
                                $value['lat'] * 3.1415926535898 / 180
                            ) * POW(
                                SIN(
                                    3.1415926535898 * ($lng - $value['lng'])
                                    / 360
                                ), 2
                            )
                        )
                    ));
                $value['distance'] = round($distance, 1);
            }

            return $data;
        }

        return false;

    }


    public function caseInfo(int $id)
    {
        // 查询书柜下所有抽屉id
        // 查询书柜下所有抽屉下图书
    }


    /**
     * 获取某柜子下所有抽屉
     *
     * @param $case_id
     *
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function drawers($case_id)
    {
        if (empty($case_id) || $case_id != null) {
            return $this->hasMany('drawer', 'pid')->where('pid', $case_id)
                ->select()->hidden(['pid'])
                ->toArray();
        }

        return false;
    }
}