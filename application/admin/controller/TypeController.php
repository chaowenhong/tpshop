<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Type;
use app\tools\Cattree;
class TypeController extends Controller
{
    /**
     * 分类显示页面
     *
     * @return \think\Response
     */
    public function index()
    {
    
        // 是否有搜索条件
        if (!empty($_GET['uname'])){
            $b=1;
            $ser = [];
            // 用户名搜索
            $ser[] = ['name','like',"%{$_GET['uname']}%"];
            $dat = Type::where($ser)->select();
        }else{
            $b=0;
            $data = Type::select();
            $vi = new Cattree($data);
            $dat = $vi->getTree();
        }
        
       // dump($dat);
        return view('/type/show',['dat'=>$dat,'b'=>$b]);
        
       
    }

    /**
     * 添加分类
     *
     * @return \think\Response
     */
    public function create($id='')
    {
        // dump($id);die;
        $date = Type::select();
        $vi = new Cattree($date);
        $dat = $vi->getTree();
        // dump($res); 
       return view('/type/showadd',['dat'=>$dat,'id'=>$id]);
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
       $shu=$request->post();
       // 进行添加
       // 判断是否为空
       if(empty($shu['name'])){
         return $this->error('分类名不能为空');
       }
       // 判断是否添加成功
        try{
              Type::create($shu,true);
        } catch (\Exception $e) {
             return $this->error('分类添加失败，请检查！');
        }
        // 添加成功
        return $this->success('添加分类成功，请查看','/admin/type_show');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
       
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
         // dump($id);
        $date = Type::select();
        $vi = new Cattree($date);
        $dat = $vi->getTree();
        $xg = Type::find($id);
         return view('/type/showedit',['dat'=>$dat,'xg'=>$xg]);
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
        //、
        $xiu = $request->post();
        $cha = Type::find($xiu['pid']);
        // 判断是否是选择了自身分类
        if($xiu['pid']==$id){
            return $this->error('不能修改到自身分类下！');
        }
        if($id==$cha['pid']){
            return $this->error('不能修改到子分类下！');
        }
        // 查询所修改的分类是否有子分类，有则不能移动
        // 判断是否是选择了低于自身的分类，只可以同级别修改分类
        try {
            Type::update($xiu,['id'=>$id]);
        } catch (Exception $e) {
             return $this->error('未知错误，修改失败');
        }
        return $this->success('修改成功，返回到浏览页面','/admin/type_show');
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
     
        // 查询有没有和已传送id相同的子分类
        $zi = Type::where(['pid'=>$id])->find();
        if($zi['pid'] == $id){
            return $this->error('该分类下有子分类，不可直接删除');
        }
        // 没有子分类就进行删除操作
        try {
            Type::destroy($id);
        } catch (Exception $e) {
            return $this->error('删除失败');
        }
        return $this->success('删除成功','/admin/type_show');
    }
    /**
     * 分类排序
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function sort(Request $request)
    {
       $shu = $request->post();
       $num =0;
       foreach($shu as $k=>$v){
         Type::update(['ord'=>$v],['id'=>$k]);
       }
       return redirect('/admin/type_show');
    }
}
