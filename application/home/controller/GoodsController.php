<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\home\model\Goods;
use app\home\model\Type;
use app\home\model\Car;
use app\common\model\Order;

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
     * 显示详情单页.
     *
     * @return \think\Response
     */
    public function xiangqing($id)
    {
       $good = Goods::where('id','=',$id)->find();
        $data = Type::select();
      
       return view('xiangqing/index',['data'=>$data,'good'=>$good]);
    }

    //购物车添加
    public function car1(Request $request ,$id)
    {
       $good_car = Goods::where('id','=',$id)->find();
       dump( $good_car);die;
         try{
             Car::create( $good_car,true);
        } catch (\Exception $e) {
             return $this->error('添加失败，请检查！');
        }
        // 添加成功
        return $this->success('添加成功，请查看','/home/car_show');
       
    }
     /**
     * 购物车添加页
     *
     * @return \think\Response
     */
    public function car(Request $request)
    {
       
      $data = $request->post();
       //dump( $data);die;
        try{
             Car::create($data,true);
        } catch (\Exception $e) {
             return $this->error('添加失败，请检查！');
        }
        // 添加成功
        return $this->success('添加成功，请查看','/home/car_show');
    }
    /**
     * 生成订单
     *
     * @return \think\Response
     */
    public function go_shop(Request $request)
    {
       
      $order = $request->post();

       $car = Car::select();
       //dump($car);die;
         $data = Type::select();
       //dump($order);die;
    return view('order/add',['data'=>$data,'order'=>$order,'car'=>$car]);
    }
     /**
     * 购物车添加显示页
     *
     * @return \think\Response
     */
    public function car_show()
    {
        $car = Car::select();

        $data = Type::select();
        //dump($car);die;
      return view('car/index',['data'=>$data,'car'=>$car]);
      
    }
      /**
     * 删除购物车商品显示页
     *
     * @return \think\Response
     */ 
    public function car_die($cid)
    {
        
       $car_die = Car::destroy($cid);
       if(Car::destroy($cid)){
            return $this->success('删除成功','/home/car_show');
         }else{
             return $this->error('删除失败','/home/car_show');

         }
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
     * 完成订单
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function wan(Request $request)
    {
        $wan = $request->post();
         
          $wan['number']=date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8); 

                   // dump($wan);die;
        try{
             Order::create($wan,true);
        } catch (\Exception $e) {
             return $this->error('支付失败，请检查！');
        }
        // 添加成功
         $data = Type::select();
       return view('wan/index',['data'=>$data,'wan'=>$wan]);
        //return view('list/list',['data'=>$data]);
    }
    


   /**
     * 显示编辑资源表单页加.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function num($cid)
    {
        $upd = Car::find($cid);

        dump($upd['num']);
        if($_GET['a'] == 'jian'){
               $upd['num'] -= 1;
               
        }else{
               $upd['num'] += 1;
        }
        if($upd['num']<1){
          $upd['num'] =1;    
        }
       
          try {
             Car::update(['num'=>$upd['num']],['cid'=>$cid]);

        } catch (Exception $e){

            return redirect('/home/car_show'); 
        }
            return redirect('/home/car_show'); 
        

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
