<?php

namespace app\http\middleware;

class infoHome
{
    public function handle($request, \Closure $next)
    {
    	if(empty(session('infohome'))){
    		return $this->error('请进行登陆后在进行访问','/home/login');
    	}
    	return $next($request);
    }
}
