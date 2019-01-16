<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\home\model\Type;
use app\home\model\Goods;
use app\tools\Cattree;
use app\home\model\Yh;
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
        // 调用无限分类
        $c = new Cattree($data);
        $data = $c->getTree();
        //dump($data);die;
       return view('/default/index',['data'=>$data]);
    }
    //登录方法
     public function login()
    {
       
       return view('denglu/index');
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
      public function search_yzm()
      {
       // $yzm = $_GET['yzm'];
       // if(empty($yzm)){
       //      return json_encode(['status'=>100]);
       // }
         $config =    [
         // 验证码字体大小
        'fontSize'    =>    15,    
         // 验证码位数
         'length'      =>    4,   
        // 关闭验证码杂点
        'useNoise'    =>    false, 
         // 是否画混淆曲线
        'useCurve' => false,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
       
      }

    //注册方法
    public function zhuce()
    {
        return view('zhuce/index');
    }

    //执行注册方法
    public function do_zhuce(Request $request)
    {
      
      $data = $request->post();
        try{
            // true过滤字段
           Yh::create($data,true);
        }catch (\Exception $e){
            return $this->error('注册失败!!!');
        }
        return $this->success('注册成功!!!','/'); 

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
