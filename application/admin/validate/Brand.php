<?php
namespace app\admin\validate;

use think\Validate;

class Brand extends Validate
{
    protected $rule = [
        'brand_name' => 'require|unique:brand',
        'brand_url'  => 'url',
        'brand_des'  => 'min:6',
    ];

    protected $message = [
        'brand_name.require' => '品牌名称必填',
        'brand_name.unique'  => '品牌名称不能重复',
        'brand_url.url'      => '网址格式不正确',
        'brand_des.min'      => '描述最少6个字符',
    ];

    protected $scene = [
        'add'    => ['brand_name', 'brand_url', 'brand_des'],
        'update' => ['brand_name', 'brand_url', 'brand_des'],
    ];

}
