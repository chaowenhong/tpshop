<?php

namespace app\home\model;

use think\Model;

class Goods extends Model
{
     //数据表名
     protected $table = 'goods';
     //主键名
     protected $pk = 'id';

     public function type(){
     	return $this->belongsTo('Type','type_id','id');
     }
}
