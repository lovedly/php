<?php
namespace app\bis\controller;
use think\Controller;
class Location extends Base
{
    private $obj;
    public function _initialize(){
        $this->obj = model("BisLocation");
    }
    /**
     * 列表页
     */
    public function index()
    {
        $bisId = $this->getLoginUser()->bis_id;
        $bislocations = $this->obj->getNormalLocationByBisId($bisId);
        return $this->fetch('',[
            'bislocations'=>$bislocations,
        ]);
    }
        
   public function add(){
       if (request()->isPost()){
           $data =input('post.');
           //校验数据
           $validate = validate('BisLocation');
           if (!$validate->scene('bisadd')->check($data)){
               $this->error($validate->getError());
           }
           
           $bisId = $this->getLoginUser()->bis_id;
           $data['cat'] ='';
           if (!empty($data['se_category_id'])){
               $data['cat'] = implode('|',$data['se_category_id']);
           }
           //门店入库操作
           $locationData =[
               'bis_id' =>$bisId,
               'name' =>$data['name'],
               'logo' => $data['logo'],
               'tel' =>$data['tel'],
               'contact'=>$data['contact'],
               'category_id' =>$data['category_id'],
               'category_path' =>empty($data['se_category_id']) ? $data['category_id'] : $data['category_id'] . ',' . $data['cat'],//防止出现7.这样不容易处理的数据
               'city_id' =>$data['city_id'],
               'city_path'=>empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
               'api_address'=>$data['address'],
               'open_time'=>$data['open_time'],
               'content'=>empty($data['content']) ? '' :$data['content'],
               'is_main'=>0,   
           ];
           $locationId = model('BisLocation')->add($locationData);
           if ($locationId){
               $this->success('门店申请成功');
           }else{
               $this->error('门店申请失败');
           }
       }else{
            //获取一级城市的数据
            $citys =model('City')->getNormalCityByParentId();
            //获取一级栏目的数据
            $categorys =model('Category')->getNormalCategoryByParentId();
                                           
            //print_r($categorys);exit;
            return $this->fetch('',[
                'citys' =>$citys,
                'categorys'=>$categorys,
            ]);
       }
   }
   
   public function detail()
   { 
       $id = input('get.id');
       if(empty($id)){
           return $this->error("ID错误");
       }
       //获取一级城市的数据
       $citys =model('City')->getNormalCityByParentId();
       //获取一级栏目的数据
       $categorys =model('Category')->getNormalCategoryByParentId();
       //获取商户数据
       
       $locationData =model('BisLocation')->get(['id' => $id,'is_main'=>0]);
       //print_r($locationData);exit;
       return $this->fetch('',[
            'citys' =>$citys,
            'categorys'=>$categorys,
           'locationData'=>$locationData,
        ]);
   }
   
   
   public function status(){
       $data = input('get.');
       $validate = validate('Bis');
       if (!$validate->scene('status')->check($data)){
           $this->error($validate->getError());
       }
       $res = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
       if($res){
           $this->success('下架成功');
       }else {
           $this->error('下架失败');
       }
   }
}
