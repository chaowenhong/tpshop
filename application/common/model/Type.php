<?php

namespace app\common\model;

use think\Model;

class Type extends Model
{
	// 分类表名字
    protected $table='data_type';
   	// 主键	
   	protected $pk = 'id';
   	// 处理名字
   	public function getDopidAttr($v){
    	$num = substr_count($this->path,',')-1;
    	$ret = str_repeat('|----',$num);
    	$name = $ret.$this['name'];
    	return $name;
    }
    // 处理名字
   	public function getDonameAttr($v){
    	$num = substr_count($this->path,',')-1;
    	$ret = str_repeat('|----',$num);
    	$name = $ret.$this['name'];
    	return $name;
    }
}
