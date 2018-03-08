<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/30
 * Time: 下午5:06
 */

namespace app\admin\validate;


use think\Validate;

class Rtype extends Validate
{
    protected $rule = [
        'name' => 'require|unique:rtype,isdel=0&name=:name',
        'imgid' => 'require',
        'imgurl' => 'require',
    ];

    protected $message = [
        'name.require'  =>  '名称不能为空',
        'name.unique'  =>  '名称已存在',
        'imgid.require'  =>  '图片ID不能为空',
        'imgurl.require'  =>  '图片url不能为空',
    ];

    protected $scene = [
        'update' => [''],
    ];
}