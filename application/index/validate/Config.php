<?php

namespace app\index\validate;

use think\Validate;

class Config extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name'=>'require|max:12',
        'address'=>'require|max:50',
        'title'=>'require|max:30',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
         'name.require'=>'网站名称不能为空',
        'name.max'=>'名称长度不能超过12个限度',
        'address.require'=>'网址不能为空',
        'address.max'=>'网址长度不能超过50个限度',
         'title.require'=>'网址标题不能为空',
        'title.max'=>'网站标题长度不能超过30个限度',
    ];
}
