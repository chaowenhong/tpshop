<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Order;

class OrderController extends Controller
{
    /**
     * 显示全部订单列表
     *
     * @return \think\Response
     */
    public function index()
    {
         // 定义一个查询条件的空数组
        $ser = [];
        // 是否有搜索条件
        if (!empty($_GET['number'])){
            // 订单号搜索
            $ser[] = ['number','like',"%{$_GET['number']}%"];
        }
          // 是否有搜索条件
        if (!empty($_GET['state'])){
            // 状态搜搜
            $ser[] = ['state','=',"{$_GET['state']}"];
        }
        // 查询数据库
       $data =  Order::where($ser)->paginate(6)->appends($_GET);
       // dump($data);die;
        return view('order/index',['data'=>$data]);
    }
    /**
     * 显示未发货订单页面
     *
     * @return \think\Response
     */
    public function newindex()
    {
         // 定义一个查询条件的空数组
        $ser = [];
        // 是否有搜索条件
        if (!empty($_GET['number'])){
            // 订单号搜索
            $ser[] = ['number','like',"%{$_GET['number']}%"];
        }
        $ser[] = ['state','=','1'];
        // 查询数据库
       $data =  Order::where($ser)->paginate(6)->appends($_GET);
       // dump($data);die;
        return view('order/newindex',['data'=>$data]);
    }
    /**
     * 发货状态
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function fa($id,$state ='2')
    {
        $state = ['state'=>$state];
         try {
             Order::update($state,['id'=>$id]);
        } catch (Exception $e) {
            return redirect('/admin/order_show');
        }
       
        return redirect('/admin/order_show');
        // 修改发货state为2
       
    }
    /**
     * 不发货
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function bufahuo($id)
    {
        return $this->fa($id,1);
        // 修改发货state为2
       
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
         
        // 查询数据,删除
         $cha = Order::destroy($id);
         if(Order::destroy($id)){
            return $this->success('删除成功','/admin/order_show');
         }else{
             return $this->error('删除失败','/admin/order_show');
         }
    }
}
