<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\home\model\Type;
use app\home\model\Goods;
use app\tools\Cattree;
use app\home\model\User;
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
        $data = Type::select();
        
       return view('/default/index',['data'=>$data]);
    }
    //登录方法
     public function login()
    {
    $data = Type::select();
       return view('denglu/index',['data'=>$data]);
    }
     // 
     // 显示注册页面
     // 
     // 
    public function zhuce()
    {
        $data = Type::select();
        return view('zhuce/index',['data'=>$data]);
    }
      /**
        检测登录账号
     */
    public function login_name()
    {

        /// 获取输入的名字
         $name = $_POST['uname'];
         if(empty($name)){
             return json_encode(['status'=>100]);
         }
         // 查询数据库中数据，看是否有该名字的数据
         $data = User::where('uname','=',$name)->find();
         if(empty($data)){
                // 如果有用户名，传送数据200
                return json_encode(['status'=>200]);
         }
                // 没有数据返回一个数据200
                return json_encode(['status'=>400]);
    } 
     /**
     * 检测登录密码
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function login_pwd()
    {
        // 获取输入的名字
         $pwd = $_POST['pwd'];
         $name = $_POST['uname'];
         if(empty($pwd)){
             return json_encode(['status'=>100]);
         }
         $pwd = md5($pwd);
         // 查询数据库中数据，核对密码
         $data = User::where('uname','=',$name)->where('pwd','=',$pwd)->find();
         // return($data);
         if(!empty($data)){
               // 如果密码正确，传送数据400
                return json_encode(['status'=>400]);
         } // 密码错误返回一个数据200
                return json_encode(['status'=>200]);
             
    } 
    /**
        检测用户名是否为空
     */
    public function sname()
    {

        // 获取输入的名字
         $name = $_POST['uname'];  
         if(empty($name)){
             return json_encode(['status'=>100]);
         }
         // 查询数据库中数据，看是否有该名字的数据
         $data = User::where('uname','=',$name)->find();
         if(!empty($data)){
                // 如果有用户名，传送数据400
                return json_encode(['status'=>200]);
         }
                // 没有数据返回一个数据200成功执行
                return json_encode(['status'=>400]);
    } 
     /**
     * 检测登录密码
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function spwd()
    {
        // 获取输入的名字
         $pwd = $_POST['pwd'];
         if(empty($pwd)){
            // 密码不能为空
             return json_encode(['status'=>100]);
         }
         if(strlen($pwd)<6){
            // 密码长度不能少于六位
               return json_encode(['status'=>200]);
         }
        // 密码输入正确
        return json_encode(['status'=>400]);
    } 
    //执行注册方法
    public function do_zhuce(Request $request)
    {
      
      $data = $request->post();
      $code = $data['yanzhen'];
      // 检测输入的验证码是否正确，$value为用户输入的验证码字符串
      $captcha = new Captcha();
      if(!$captcha->check($code))
      {
        // 验证失败
        return $this->error('验证码错误，请重新输入');
      }
      $name = $data['uname'];
      if(empty($name)){
        return $this->error('账号不能为空，请填写');
      }
     if(empty($data['pwd'])){
        return $this->error('密码不能为空，请填写');
      }
      if($data['pwd']!=$data['repwd']){
        return $this->error('两次密码输入不一致,请检查');
      }
      // 查询数据
      $da = User::where('uname','=',$name)->find();
      if(!empty($da)){
        return $this->error('该账号已经存在，重新输入账号名');
      }
      $data['pwd'] = md5($data['pwd']);
        try{
            // true过滤字段
           User::create($data,true);
        }catch (\Exception $e){
            return $this->error('注册失败!!!');
        }
        return $this->success('注册成功!前往登录','/home/login/'); 

    }
    //登录的执行方法
    public function dologin(Request $request)
    {

      $data = $request->post();
      $code = $data['yanzhen'];
      // 检测输入的验证码是否正确，$value为用户输入的验证码字符串
      $captcha = new Captcha();
      if(!$captcha->check($code))
      {
        // 验证失败
        return $this->error('验证码错误，请重新输入');
      }
      $name = $data['uname'];
      $pwd = md5($data['pwd']);
      // 查询数据
      $da = User::where('uname','=',$name)->where('pwd','=',$pwd)->find();
      if(empty($da)){
        return $this->error('账号或者密码不正确，请检查');
      }
      
      if($da['state']==2){
        return $this->error('该账号已经被禁止登录','/admin/login_show');
      }
      // 保存用户登录的信息
      session('home_user',$da);
      // 保存一个数据，验证登录
      session('infohome',true);
      return $this->success('登录成功！！','/');
    }
    
    //检测验证码的方法
    public function yanzhen()
    {
        $config =    [
         // 验证码字体大小
        'fontSize'    => 20,    
         // 验证码位数
         'length'      => 4,   
        // 关闭验证码杂点
        'useNoise'    => false, 
         // 是否画混淆曲线
        'useCurve' => true,
        // 验证码长度
        'imageW' => 180,
        // 验证码长度
        'imageH' => 40,
        // 背景颜色
        'bg' => [240, 220, 220],
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
