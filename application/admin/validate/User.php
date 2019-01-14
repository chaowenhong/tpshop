<?php

namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'uname'=>'require|max:8',
        'pwd'=>'require|min:6|alphaDash',
        'tel'=>'mobile',
        'email'=>'email',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'uname.require'=>'名称不能为空',
        'uname.max'=>'名称长度不能超过8个限度',
        'pwd.require'=>'密码不能为空',
        'pwd.max'=>'密码长度不能低于6位',
        'pwd.alphaDash'=>'密码必须是由字母和数字，下划线 _ 及破折号 - 组成',
        'tel.mobile'=>'手机号格式不正确',
        'email.email'=>'邮箱格式不正确，请检查',

    ];
}
