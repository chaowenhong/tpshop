<?php

namespace app\home\model;

use think\Model;

class Type extends Model
{
   //数据表名
   protected $table = 'data_type';
   //表主键
   protected $pk = 'id';

   public function getTypenameAttr(){
   	$n = substr_count($this->path,',')-1;
   	$space = str_repeat('&nbsp',$n*6);
   	$name = $space.$this['name'];
   	return $name;
   }
}
