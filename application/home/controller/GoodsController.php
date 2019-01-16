<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\home\model\Goods;
use app\home\model\Type;
class GoodsController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function goods($id='')
    {
        $search = [];
        if(!empty($id)){
            $selec = Type::where('pid','=',$id)->column('id');
            // $selec[] = (int)$id;
            $search[] = ['tid', 'in' ,$selec];
        }
        $good = Goods::where( $search )->select();
        $data = Type::select();
       // dump($good);die;
      return view('liebiao/index',['data'=>$data,'good'=>$good]);
      
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
     * 购物车方法
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function list($id)
    {
        $data = Goods::find($id);
        //dump($data);die;
        return view('list/list',['data'=>$data]);
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
