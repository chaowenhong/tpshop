<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Login;
use app\common\model\User;
use think\captcha\Captcha;
class LoginController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return view('/login/show');
    }
     /**
     * 再次检测是否输入密码
     *
     * @return \think\Response
     */
    public function dologin(Request $re)
    {
    
      $data = $re->post();
      $code = $data['yanzhen'];
      // 检测输入的验证码是否正确，$value为用户输入的验证码字符串
      $captcha = new Captcha();
      if(!$captcha->check($code))
      {
        // 验证失败
        return $this->error('验证码错误，请重新输入','/admin/login_show');
      }
      $name = $data['name'];
      $pwd = md5($data['pwd']);
      // 查询数据
      $da = User::where('uname','=',$name)->where('pwd','=',$pwd)->find();
      if(empty($da)){
        return $this->error('账号或者密码不正确，请检查','/admin/login_show');
      }
      // 判断是不是管理员
      if($da['power']<3){
        return $this->error('该账号不是管理员','/admin/login_show');
      }
      if($da['state']==2){
        return $this->error('该账号已经被禁止登录','/admin/login_show');
      }
      // 保存用户登录的信息
      session('user',$da);
      // 保存一个数据，验证登录
      session('infoAdmin',true);
      return $this->success('登录成功！！','/admin/admin_index');
    }
      /**
     * 退出登陆
     *
     * @return \think\Response
     */
    public function out()
    {
        session('infoAdmin',false);
        return $this->success('退出成功！','/admin/login_show');
    }
    /**
     * 验证码
     *
     * @return \think\Response
     */
   public function verify()
    {
        $config = [// 验证码字体大小
        'fontSize' => 20,
        // 验证码位数
        'length' => 4,
        // 验证码高度
        'imageH' => 40,
        // 验证码宽度
        'imageW' => 135,
        // 验证码混淆点
        'useNoise' => false,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
