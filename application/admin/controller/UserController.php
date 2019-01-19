<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\User;
class UserController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {   
        // 定义一个查询条件的空数组
        $ser = [];
        // 是否有搜索条件
        if (!empty($_GET['uname'])){
            // 用户名搜索
            $ser[] = ['uname','like',"%{$_GET['uname']}%"];
        }
          // 是否有搜索条件
        if (!empty($_GET['sex'])){
            // 用户名搜索
            $ser[] = ['sex','=',"{$_GET['sex']}"];
        }
           // 是否有搜索条件
        if (!empty($_GET['power'])){
            // 用户名搜索
            $ser[] = ['power','=',"{$_GET['power']}"];
        }
          // 是否有搜索条件
        if (!empty($_GET['state'])){
            // 状态搜搜
            $ser[] = ['state','=',"{$_GET['state']}"];
        }
        $dat =  User::where($ser)->paginate(6)->appends($_GET);
         $date = session('user');
         $daa = User::where('uid','=',$date['uid'])->find();
        //用户列表显示
       return view('/user/index',['dat'=>$dat,'daa'=>$daa]);
    }
      /**
     *个人中心显示
     *
     * @return \think\Response
     */
    public function onuser()
    {   

        $date = session('user');
        $data = User::where('uid','=',$date['uid'])->find();
        // dump($data);die;
        //用户列表显示
       return view('/user/onuser',['data'=>$data]);
    }
    /**
     * 禁用用户页面
     *
     * @return \think\Response
     */
    public function jin()
    {   
        // 禁用用户页面
        // 定义一个查询条件的空数组
        $ser = [];
        // 是否有搜索条件
        if (!empty($_GET['uname'])){
            // 用户名搜索
            $ser[] = ['uname','like',"%{$_GET['uname']}%"];
        }
          // 是否有搜索条件
        if (!empty($_GET['sex'])){
            // 用户名搜索
            $ser[] = ['sex','=',"{$_GET['sex']}"];
        }
        $ser[] = ['state','=','2'];
        $dat =  User::where($ser)->paginate(5)->appends($_GET);
        //用户列表显示
       return view('/user/jin',['dat'=>$dat]);
    }
     /**
     * 禁用操作
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function dojin($id,$sta = '2')
    {
        $state = ['state'=>$sta];
        $date = session('user');
        try {
             User::update($state,['uid'=>$id]);
        } catch (Exception $e) {
            return redirect('/admin/user_show');
        }
       
        return redirect('/admin/user_show');
    }
    /**
     * 启用操作
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function doqi($id)
    {
       return $this->dojin($id,'1');
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        // 添加用户
        return view('/user/add');
    }

    /**
     * 密码操作
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request->post();
        $validate = new \app\admin\validate\User;
        if (!$validate->check($data)) {
         return $this->error($validate->getError());
        }
        // 说明原来密码一致,在判断是否输入了新密码
            if(empty($data['pwd']|| $data['repwd'])){
                 return $this->error('密码或者确认密码不能为空');
            }
        // 判断密码是否一致
           if($data['pwd'] != $data['repwd']){
            return $this->error('密码不一致，请重新输入');
        }
        $file = $request->file('upic');
        if(!empty($file)){
             // 移动上传图片到指定位置
            $info = $file->move('upic');
            // 获取图片的路径
            $addr = $info->getSaveName();
            // 设置文件路
            $data['upic'] = $addr; 
        }
        // 判断是否添加成功
        try{
              User::create($data,true);
        } catch (\Exception $e) {
             return $this->error('用户添加失败，请检查！');
        }
        // 添加成功
        return $this->success('添加用户成功，请查看','/admin/user_show');
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
        $upd = User::find($id);
        // dump($upd);die;
        // 显示修改页面
        return  view('/user/update');
    }

    /**
     * 修改资料操作
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $date = $request->post();
        $idd = session('user');
        // dump($date);
        // dump($id);
        // dump($idd);die;
         // 获取隐藏于传送的值
        $file = $request->file();
        // 判断是否修改了原图片
        if(empty($file)){
            // 没有修改原图
            $date['upic'] = $date['ypic'];
        }else{
            // 移动上传图片到指定位置
            $info = $file['pic']->move('upic');
            // 获取图片的路径
            $addr = $info->getSaveName();
            // 保存到数组
            $date['upic'] = $addr; 
            if($date['ypic']){
                // 获取原图片路径并且删除
               $ypic = 'upic/'.$date['ypic'];
               unlink($ypic); 
               
            }
        } 
        // 检测修改的是否是已经登录的用户
        if($id == $idd['uid']){
                // 如果是
                try {
                 User::update($date,['uid'=>$id]);
                }catch(Exception $e) {
                    return $this->error('修改失败，请检查','/admin/user_show');
                }
                 //退出登录
                session('infoAdmin',false);
                return $this->success('当前登录用户信息已经更新，请重新登录','/admin/login_show');
        }else{
                try {
                     User::update($date,['uid'=>$id]);
                } catch (Exception $e) {
                   return $this->error('修改失败，请检查','/admin/user_show');
                }
                 
                return $this->success('修改成功！','/admin/user_show');
             }
        }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        
         // 查询图片
         $tu = User::find($id);
         // 查询数据
         $cha = User::destroy($id);
          if($tu['upic']){
                $ypic = 'upic/'.$tu['upic'];
                unlink($ypic); 
            }
         if(User::destroy($id)){
            return $this->success('删除成功','/admin/user_show');
         }else{
             return $this->error('删除失败','/admin/user_show');
         }
      
    }
     /**
     * 修改密码页面
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function pwd($id)
    {
         $pwd = User::find($id);
        return view('/user/pwd',['pwd'=>$pwd]);
    }
    /**
     * 修改密码
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function dopwd(Request $request, $id)
    {
        // 获取原来密码
        $ysj=User::find($id);
       // 获取输入的值
        $pwd = $request->get();

        // 判断旧密码是否填写正确
        if(md5($pwd['ypwd'])==$ysj['pwd']){
            // 说明原来密码一致,在判断是否输入了新密码
            if(empty($pwd['pwd'] || $pwd['rnpwd'])){
                 return $this->error('新密码或者确认密码不能为空');
            }
            if($pwd['pwd']!=$pwd['rnpwd']){
                return $this->error('两次密码输入不一致,请检查');
            }else{
                    try{
                     User::update($pwd,['uid'=>$id]);
                    } catch (Exception $e) {
                        return $this->error('修改失败，请检查');
                    }
                    return $this->success('修改成功密码！','/admin/user_show'); 
                   
            }
        } 
        return $this->error('原密码错误');
    }
    
     /**
     * 检测登录用户名
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function sname()
    {
        // 获取输入的名字
         $name = $_POST['name'];
         if(empty($name)){
             return json_encode(['status'=>100]);
         }
         // 查询数据库中数据，看是否有该名字的数据
         $data = User::where('uname','=',$name)->find();
         if(!empty($data)){
                // 如果有用户名，传送数据400
                return json_encode(['status'=>400]);
         }
                // 没有数据返回一个数据200
                return json_encode(['status'=>200]);
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
         $name = $_POST['name'];
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
         }
                // 密码错误返回一个数据200
                return json_encode(['status'=>200]);
    } 
}
