<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//定义前台首页
Route::rule('/','home/LoginController/index');

//定义前台登录页
Route::rule('/home/login','home/LoginController/login');
//定义前台注册页面
Route::rule('/home/zhuce','home/LoginController/zhuce');
//定义执行注册页面
Route::rule('/home/do_zhuce','home/LoginController/do_zhuce');
//检测用户注册的是否存在
Route::rule('/home/search_user','home/LoginController/search_user');
//定义执行前台登录
Route::rule('/home/do_denglu','home/LoginController/do_denglu');
//执行验证码的页面
Route::rule('/home/search_yzm','home/LoginController/search_yzm');

// 登录
Route::rule('/admin/login_show','admin/LoginController/index');
// 登录名字检测
Route::rule('/admin/sname','admin/UserController/sname');
// 登录密码检测
Route::rule('/admin/spwd','admin/UserController/spwd');
// 登录再次检测
Route::rule('/admin/dologin','admin/LoginController/dologin');
// 验证码检测
Route::rule('/admin/verify','admin/LoginController/verify');
// 登陆组
Route::group([],function(){
	// 后台首页
	Route::rule('/admin/admin_index','admin/AdminController/index');

	// 退出登录
	Route::rule('/admin/login_outs','admin/LoginController/out');

	// 用户显示列表
	Route::get('/admin/user_show','admin/UserController/index');
	// 禁用用户显示列表
	Route::get('/admin/user_jin','admin/UserController/jin');
	// 禁用操作
	Route::rule('/admin/user_no/:id','admin/UserController/dojin');
	// 启用操作
	Route::rule('/admin/user_qi/:id','admin/UserController/doqi');
	// 添加用户
	Route::get('/admin/user_add','admin/UserController/create');
	// 添加操作
	Route::post('/admin/user_save','admin/UserController/save');
	// 删除操作
	Route::get('/admin/user_delete/:id','admin/UserController/delete');
	// 修改页面
	Route::get('/admin/user_update/:id','admin/UserController/edit');
	// 修改操作
	Route::post('/admin/user_doupd/:id','admin/UserController/update');
	// 修改密码页面
	Route::get('/admin/user_pwd/:id','admin/UserController/pwd');
	// 修改密码
	Route::get('/admin/user_dopwd/:id','admin/UserController/dopwd');
	// 个人中心
	Route::rule('/admin/onuser','admin/UserController/onuser');
})->middleware('InfoAdmin');

// 分类操作组
Route::group(['name'=>'/admin/','prefix'=>'admin/TypeController/'],function(){
	// 分类列表显示
	Route::rule('type_show','index');
	// 添加分类页面
	Route::rule('type_showadd/[:id]','create');
	// 添加分类操作
	Route::rule('type_save','save');
	// 分类排序操作
	Route::rule('type_sort','sort');
	// 删除分类操作
	Route::rule('type_delete/:id','delete');
	// 分类修改页面
	Route::rule('type_edit/:id','edit');
	// 分类修改操作
	Route::rule('type_update/:id','update');
	
})->middleware('InfoAdmin');

// 商品管理组
Route::group(['name'=>'/admin/','prefix'=>'admin/GoodsController/'],function(){
	// 商品显示列表
	Route::rule('goods_show','index');
	// 商品搜索
	Route::rule('goods_sou','index');
	// 商品添加显示
	Route::rule('goods_create','create');
	// 商品添加操作
	Route::rule('goods_save','save');
	// 商品删除操作
	Route::rule('goods_delete/:id','delete');
	// 商品修改显示
	Route::rule('goods_edit/:id','edit');
	// 商品修改操作
	Route::rule('goods_update/:id','update');
})->middleware('InfoAdmin');
// 订单管理组
Route::group(['name'=>'/admin/','prefix'=>'admin/OrderController/'],function(){
	// 订单列表
	Route::rule('order_show','index');
	// 订单发货
	Route::rule('order_fa/:id','fa');
	// 订单不发货
	Route::rule('order_bufa/:id','bufahuo');
	// 删除订单
	Route::rule('order_delete/:id','delete');
	// 未发货订单显示
	Route::rule('order_new','newindex');
})->middleware('InfoAdmin');
// 其他设置组
Route::group(['name'=>'/admin/','prefix'=>'admin/OtherController/'],function(){
	// 配置列表
	Route::rule('config','cindex');
	// 修改配置
	Route::rule('save/:id','save');
	// 关闭网站
	Route::rule('config_g/:id','config_g');
	// 开启网站
	Route::rule('config_k/:id','config_k');
	// 友情链接
	Route::rule('friend/[:id]','findex');
	// 添加链接
	Route::rule('fsave','fsave');
	// 删除链接
	Route::rule('fdelete/[:id]','fdelete');
	// 关闭友情链接
	Route::get('friendg/:id','friendg');
	// 开启友情链接
	Route::get('friend_k/:id','friend_k');
	// 修改友情链接
	Route::rule('f_create/:id','f_create');
})->middleware('InfoAdmin');