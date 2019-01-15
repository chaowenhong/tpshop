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
    public function findex()
    {
        return view('friend/index');
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
