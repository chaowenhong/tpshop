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
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
         'name.require'=>'网站名称不能为空',
        'name.max'=>'名称长度不能超过8个限度',
    ];
}
