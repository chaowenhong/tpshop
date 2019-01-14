<?php

namespace app\http\middleware;
class InfoAdmin
{
	use \traits\controller\Jump;
    public function handle($request, \Closure $next)
    {
    	if(empty(session('infoAdmin'))){
    		return $this->error('请进行登陆后在进行访问','/admin/login_show');
    	}
    	return $next($request);
    }
}
