<?php

namespace app\common\model;

use think\Model;

class User extends Model
{
    //数据表名字
   protected $table='data_user';
   	// 表主键
    protected $pk = 'uid';
    // 密码加密 修改器
    public function setPwdAttr($v){
    	return md5($v);
    }
}
