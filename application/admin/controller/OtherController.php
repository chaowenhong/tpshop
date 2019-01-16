<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Other;
use app\common\model\friend;
class OtherController extends Controller
{
    /**
     * 显示网站配置
     *
     * @return \think\Response
     */
    public function cindex(Request $request)
    {
        $data = Other::find();
        // dump($data);die;    
        return view('config/index',['data'=>$data]);
    }
    /**
     * 关闭网站
     *
     * @return \think\Response
     */
    public function config_g($id,$sta=2)
    {
         $state = ['statue'=>$sta];
        try {
             Other::update($state,['id'=>$id]);
        } catch (Exception $e) {
            return redirect('/admin/config');
        }
       
        return redirect('/admin/config');
    }
    /**
     * 打开网站
     *
     * @return \think\Response
     */
    public function config_k($id)
    {
        return $this->config_g($id,1);
    }
     /**
     * 显示友情链接
     *
     * @return \think\Response
     */
    public function findex($id=1)
    {
        // echo 2;die;
        $datt =friend::where('id','=',$id)->select();
        // dump($datt);
         $data = friend::select();
        return view('friend/index',['datt'=>$datt,'data'=>$data,'id'=>$id]);
    }
     /**
     * 添加友情链接
     *
     * @return \think\Response
     */
    public function fsave(Request $request)
    {
        $data =$request->post();
        $file =$request->file('pic');

        // 检测数据是否填写完整
        if(empty($data['name'] && $data['address'])){
            return $this->error('数据必须填写完整');
        }
        // 检测图片是否上传
        if(empty($file)){
            return $this->error('链接必须添加一张图片');
        }else{
             // 移动上传图片到指定位置
            $info = $file->move('friend');
            // 获取图片的路径
            $addr = $info->getSaveName();
            // 设置文件路
            $data['pic'] = $addr; 
        }
        // 所有信息完成后进行上传到数据库
        // 判断是否添加成功
        try{
              friend::create($data,true);
        } catch (\Exception $e) {
             return $this->error('友情链接添加失败，请检查！');
        }
        // 添加成功
        return $this->success('友情链接添加成功，请查看');
    }
    /**
     * 修改
     *
     * @return \think\Response
     */
    public function f_create(Request $request)
    {
        $data = $request->post();
        $file = $request->file();
         dump($data);
        dump($file);
         die;
        $id = $data['pid'];
        dump($data);
        
        if(empty($file)){
            $data['pic']=$data['ypic'];
        }else{
            // 移动上传图片到指定位置
            $info = $file->move('friend');
            // 获取图片的路径
            $addr = $info->getSaveName();
            // 设置文件路
            $data['pic'] = $addr; 
            if($data['ypic']){
                // 获取原图片路径并且删除
               $ypic = 'friend/'.$data['ypic'];
               unlink($ypic); 
               
            }

        }

         try {
                friend::update($data,['id'=>$id]);
            } catch (Exception $e) {
               return $this->error('修改失败，请检查');
            }
            return $this->success('修改友情链接成功！');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request,$id)
    {
        $data =$request->post();
        $file = $request->file();
        // dump($data);
        // dump($file['wpic']);
        // die;
        // 进行字段检测
        $validate = new \app\index\validate\Config;
        // 如果字段检测有错进行报错
        if (!$validate->check($data)) {
         return $this->error($validate->getError());
        }
        // 判断是否上传了新图
        if(empty($file['pic'])){
            // 说明没有图片上传，保存原有的图片
            $data['pic'] = $data['ypic'];

        }else{
            // 说明有图片上传
            // 进行上传操作
            // 移动上传图片到指定位置
            $info = $file['pic']->move('configs');
            // 获取图片的路径
            $addr = $info->getSaveName();
            // 保存到数组
            $data['pic'] = $addr; 
            $ypic = 'configs/'.$data['ypic'];
            if(file_exists($ypic)){
                // 获取原图片路径并且删
               unlink($ypic); 
            }
        }
          // 判断是否上传了新图
        if(empty($file['wpic'])){
            // 说明没有图片上传，保存原有的图片
            $data['wpic'] = $data['ywpic'];

        }else{
            // 说明有图片上传
            // 进行上传操作
            // 移动上传图片到指定位置
            $info = $file['wpic']->move('configs/weihu');
            // 获取图片的路径
            $addr = $info->getSaveName();
            // 保存到数组
            $data['wpic'] = $addr; 
            $ywpic = 'configs/weihu/'.$data['ywpic'];
            if(file_exists($ywpic)){
                // 获取原图片路径并且删
               unlink($ywpic); 
            }
        }
        // 进行修改
         try {
                     Other::update($data,['id'=>$id]);
                } catch (Exception $e) {
                   
                   
                    return $this->error('修改失败，请检查','/admin/config');
                }
                return $this->success('修改成功！','/admin/config');
    }

    /**
     * 删除友情链接
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function fdelete($id)
    {
        try {
            friend::destroy($id);
        } catch (Exception $e) {
            return $this->error('删除失败');
        }return $this->success('删除成功');
    }
    /**
     * 关闭友情链接
     *
     * @return \think\Response
     */
    public function friendg($id,$sta='2')
    {
         $state = ['statue'=>$sta];
        try {
             friend::update($state,['id'=>$id]);
        } catch (Exception $e){
            return redirect('/admin/friend');
        }
       
        return redirect('/admin/friend');
    }
    /**
     * 打开友情链接
     *
     * @return \think\Response
     */
    public function friend_k($id)
    {
        return $this->friendg($id,1);
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
