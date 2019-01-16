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
        try{
            // true过滤字段
           User::create($data,true);
        }catch (\Exception $e){
            return $this->error('注册失败!!!');
        }
        return $this->success('注册成功!前往登录','/home/login/'); 

    }
    //登录的执行方法
    public function do_denglu(Request $request)
    {

       $data = $request->post();
        $uname = $data['user'];
        //判断用户名不能为空
        if(empty($uname)){
            return $this->error('用户名不能为空','/home/login');
        }
        $zym = $data['yzm']; 
        
        $captcha = new captcha();
         if(!$captcha->check($zym))
         {
            //验证码错误
            return $this->error('验证码错误','/home/login');
        }
       
        $pwd = $request->post('pwd',null,'md5');
        $res = Yh::where('uname','=',$uname)->where('pwd','=',$pwd)->find();
        if(empty($res)){
         return $this->error('用户名或密码错误','/home/login');
        }
        //保存一条的数据来验证是否登录
        session('dengluAdmin',true);
        //保存用户信息
        session('user',$res);
       return $this->success('登录成功!!!','/');
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
