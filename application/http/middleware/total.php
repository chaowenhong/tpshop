<?php

namespace app\http\middleware;
use app\common\model\Other;
class total
{
	use \traits\controller\Jump;
    public function handle($request, \Closure $next)
    {
    		$order= Other::find();
    		if($order['statue'] == 2){
    			return view('weihu/index',['order'=>$order]);
    		} return $next($request);
    }
}
