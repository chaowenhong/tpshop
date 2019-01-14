<?php

namespace app\common\model;

use think\Model;
use app\common\model\Type;
class Goods extends Model
{
   	// 分类表名字
    protected $table='data_goods';
   	// 主键	
   	protected $pk = 'id';
   	// 搜索
   public function type(){
   		return $this->belongsTo('Type','tid','id');
   }    // 处理名字
   	public function getDonameAttr($v){
    	$num = substr_count($this->path,',')-1;
    	$ret = str_repeat('|----',$num);
    	$name = $ret.$this['name'];
    	return $name;
    }
}
