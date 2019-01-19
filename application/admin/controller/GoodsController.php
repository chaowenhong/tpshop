<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Goods;
use app\common\model\Type;
use app\tools\Cattree;

class GoodsController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $ser = [];
        // 是否有搜索条件
        if (!empty($_GET['uname'])){
            // 用户名搜索
            $ser[] = ['name','like',"%{$_GET['uname']}%"];
        }
        if (!empty($_GET['statu'])){
            // 状态搜索
            $ser[] = ['status','=',"{$_GET['statu']}"];
        }
         if (!empty($_GET['tid'])){
            // $cid = $_GET['tid']
            // $qid = Type::where(['pid','=',$cid])->select();
            // dump($qid);
            // 分类搜索
            $ser[] = ['tid','=',"{$_GET['tid']}"];
        }
        $data =  Goods::where($ser)->paginate(5)->appends($_GET);
         // 查询分类数据
        $date = Type::select();
        // 处理分类数据
        $a = new Cattree($date);
        // 获取处理后的数据
        $datas = $a->getTree();
       
        // dump($data);
        return view('goods/index',['data'=>$data,'datas'=>$datas]);
    }

    /**
     * 显示添加商品.
     *
     * @return \think\Response
     */
    public function create()
    {
        // 查询分类数据
        $data = Type::select();
        // 处理分类数据
        $a = new Cattree($data);
        // 获取处理后的数据
        $datas = $a->getTree();
        // 查询数据库的数据
        $dat = Goods::select();
        return view('goods/showadd',['datas'=>$datas]);
    }

    /**
     * 保存添加商品
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 获取传过来的数据
        $data = $request->post();
        // 获取传过来的图片信息
         $file = $request->file('pic');
         // 至少填写商品名字
        if(empty($data['name'])){
            return $this->error('请填写商品名称');
        }
        // 至少填写商品价格
        if(empty($data['price'])){
            return $this->error('请标明商品价格');
        }
        if(!empty($file)){
            // 移动上传图片到指定位置
            $info = $file->move('photo');
            // 获取图片的路径
            $addr = $info->getSaveName();
         
            // 生成一个新图片名字
            $newname = str_replace('\\', '/', $addr);
            // 加入名字
            $data['pic'] = $newname; 
        }
             
        // dump($data);die;
        try {
             Goods::create($data,true);
        } catch (\Exception $e) {
            return $this->error('添加失败','/admin/goods_create');
        }return $this->success('添加成功','/admin/goods_show');
       
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
        // 查询分类数据
        $data = Type::select();
        // 处理分类数据
        $a = new Cattree($data);
        // 获取处理后的数据
        $date = $a->getTree();
        // 查询数据
        $datas= Goods::find($id);
        // 显示修改页面
        return  view('goods/update',['datas'=>$datas,'date'=>$date]);
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
        $shu = $request->post();
        $file = $request->file();
        // dump($file['pic']);die;
        // 判断是否修改了原图片
        if(empty($file)){
            // 没有修改原图
            $shu['pic'] = $shu['ypic'];
        }else{
            // 移动上传图片到指定位置
            $info = $file['pic']->move('photo');
            // 获取图片的路径
            $addr = $info->getSaveName();
            // 生成一个新图片名字
            $newname = str_replace('\\', '/', $addr);
          
            $shu['pic'] = $newname; 
            if(file_exists($shu['ypic'])){
                // 获取原图片路径并且删除
               $ypic = 'photo/'.$shu['ypic'];
                // 获取原图片缩略图进行删除
                $spic = 'photo/'.$shu['ypic'];
                 unlink($spic);
               unlink($ypic); 
               
            }
            
        }
        // 进行修改操作
        try {
             Goods::update($shu,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error('修改失败，请检查');
        }
        return $this->success('修改成功！','/admin/goods_show');
       
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
        $tu = Goods::find($id);
        // 删除数据
        $cha = Goods::destroy($id);
        if(Goods::destroy($id)){
            // 获取原图片路径并且删除
            if($tu['pic']){
                $ypic = 'photo/'.$tu['pic'];
                unlink($ypic); 
            }
            
            return $this->success('删除成功','/admin/goods_show');
         }else{
             return $this->error('删除失败');
         }
    }
}
